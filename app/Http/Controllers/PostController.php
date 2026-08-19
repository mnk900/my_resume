<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostLike;
use App\Models\PostComment;
use App\Models\PostCommentLike;
use App\Models\PostHistory;
use App\Models\Opportunity;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Professional Social Feed.
     */
    public function index()
    {
        $posts = Post::where('status', 'published')
            ->with([
                'user.portfolio',
                'company',
                'opportunity.company',
                'originalPost.user.portfolio',
                'originalPost.company',
                'likes',
                'histories.user',
                'comments' => function ($q) {
                    $q->whereNull('parent_id')->with(['user.portfolio', 'likes', 'replies.user.portfolio', 'replies.likes'])->latest();
                }
            ])
            ->latest()
            ->paginate(15);

        return view('feed.index', compact('posts'));
    }

    /**
     * Create a new post.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:3000',
            'company_id' => 'nullable|exists:companies,id',
            'image' => 'nullable|image|max:4096',
        ]);

        $companyId = $validated['company_id'] ?? null;

        if ($companyId) {
            $userCompany = Auth::user()->companies()->where('companies.id', $companyId)->first();
            if (!$userCompany && !Auth::user()->isAdmin()) {
                return back()->with('error', 'You are not authorized to post on behalf of this company.');
            }
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('post_images', 'public');
        }

        Post::create([
            'user_id' => Auth::id(),
            'company_id' => $companyId,
            'content' => $validated['content'],
            'image_path' => $imagePath,
            'post_type' => $companyId ? 'company_update' : 'general',
            'status' => 'published',
        ]);

        return back()->with('success', 'Post published to professional feed!');
    }

    /**
     * Update post and save revision history.
     */
    public function update(Request $request, Post $post)
    {
        if ($post->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            return back()->with('error', 'You are not authorized to edit this post.');
        }

        $validated = $request->validate([
            'content' => 'required|string|max:3000',
            'image' => 'nullable|image|max:4096',
        ]);

        // Save current post state into PostHistory before updating
        PostHistory::create([
            'post_id' => $post->id,
            'user_id' => Auth::id(),
            'previous_content' => $post->content,
            'previous_image_path' => $post->image_path,
        ]);

        $data = [
            'content' => $validated['content'],
        ];

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('post_images', 'public');
        }

        $post->update($data);

        return back()->with('success', 'Post updated and revision history saved.');
    }

    /**
     * Delete post.
     */
    public function destroy(Post $post)
    {
        if ($post->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            return back()->with('error', 'You are not authorized to delete this post.');
        }

        $post->delete();

        return back()->with('success', 'Post deleted.');
    }

    /**
     * Reshare a public post as a new feed post.
     */
    public function reshare(Request $request, Post $post)
    {
        if ($post->status !== 'published') {
            return back()->with('error', 'Only public posts can be reshared.');
        }

        $request->validate([
            'content' => 'nullable|string|max:1500',
            'company_id' => 'nullable|exists:companies,id',
        ]);

        $companyId = $request->input('company_id');
        if ($companyId) {
            $userCompany = Auth::user()->companies()->where('companies.id', $companyId)->first();
            if (!$userCompany && !Auth::user()->isAdmin()) {
                return back()->with('error', 'You are not authorized to post on behalf of this company.');
            }
        }

        // Increment original post's share counter
        $post->increment('shares_count');

        // Create new post referencing original_post_id
        $resharedPost = Post::create([
            'user_id' => Auth::id(),
            'company_id' => $companyId,
            'original_post_id' => $post->id,
            'content' => $request->input('content'),
            'post_type' => 'reshare',
            'status' => 'published',
        ]);

        // Notify original post author
        if ($post->user_id !== Auth::id()) {
            $this->notificationService->notify(
                $post->user,
                "Post Reshared",
                Auth::user()->name . " reshared your post to their professional feed.",
                "post_reshare",
                route('feed.index'),
                Auth::user()
            );
        }

        return back()->with('success', 'Post reshared successfully!');
    }

    /**
     * Share / Repost an opportunity or existing post.
     */
    public function shareOpportunity(Request $request, Opportunity $opportunity)
    {
        $request->validate([
            'content' => 'nullable|string|max:1000',
        ]);

        Post::create([
            'user_id' => Auth::id(),
            'opportunity_id' => $opportunity->id,
            'content' => $request->input('content') ?? "Shared an opportunity: {$opportunity->title}",
            'post_type' => 'job_share',
            'status' => 'published',
        ]);

        return back()->with('success', 'Opportunity shared to your professional feed!');
    }

    /**
     * Toggle post like.
     */
    public function toggleLike(Post $post)
    {
        $like = PostLike::where('post_id', $post->id)->where('user_id', Auth::id())->first();

        if ($like) {
            $like->delete();
            $post->decrement('likes_count');
            return back()->with('success', 'Unliked post.');
        }

        PostLike::create([
            'post_id' => $post->id,
            'user_id' => Auth::id(),
        ]);
        $post->increment('likes_count');

        if ($post->user_id !== Auth::id()) {
            $this->notificationService->notify(
                $post->user,
                "Post Liked",
                Auth::user()->name . " liked your post.",
                "post_like",
                route('feed.index'),
                Auth::user()
            );
        }

        return back()->with('success', 'Liked post.');
    }

    /**
     * Add comment or nested reply to post.
     */
    public function storeComment(Request $request, Post $post)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:post_comments,id',
        ]);

        $parentId = $request->input('parent_id');
        $parentComment = null;
        if ($parentId) {
            $parentComment = PostComment::find($parentId);
        }

        $comment = PostComment::create([
            'post_id' => $post->id,
            'user_id' => Auth::id(),
            'parent_id' => $parentId,
            'comment' => $request->input('comment'),
        ]);

        $post->increment('comments_count');

        // Notify recipient
        if ($parentComment && $parentComment->user_id !== Auth::id()) {
            $this->notificationService->notify(
                $parentComment->user,
                "New Reply to Your Comment",
                Auth::user()->name . " replied to your comment on a post.",
                "comment_reply",
                route('feed.index'),
                Auth::user()
            );
        } elseif (!$parentComment && $post->user_id !== Auth::id()) {
            $this->notificationService->notify(
                $post->user,
                "New Comment on Your Post",
                Auth::user()->name . " commented on your post.",
                "post_comment",
                route('feed.index'),
                Auth::user()
            );
        }

        return back()->with('success', $parentId ? 'Reply added successfully!' : 'Comment added.');
    }

    /**
     * Toggle comment like.
     */
    public function toggleCommentLike(PostComment $comment)
    {
        $like = PostCommentLike::where('post_comment_id', $comment->id)->where('user_id', Auth::id())->first();

        if ($like) {
            $like->delete();
            $comment->decrement('likes_count');
            return back()->with('success', 'Unliked comment.');
        }

        PostCommentLike::create([
            'post_comment_id' => $comment->id,
            'user_id' => Auth::id(),
        ]);
        $comment->increment('likes_count');

        if ($comment->user_id !== Auth::id()) {
            $this->notificationService->notify(
                $comment->user,
                "Comment Liked",
                Auth::user()->name . " liked your comment.",
                "comment_like",
                route('feed.index'),
                Auth::user()
            );
        }

        return back()->with('success', 'Liked comment.');
    }
}
