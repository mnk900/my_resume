@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="h3 fw-bold text-dark mb-1"><i class="fa-solid fa-square-rss text-primary me-2"></i> Professional Social Feed</h1>
            <p class="text-secondary mb-4">Share career updates, job opportunities, achievements, and network with professionals.</p>

            <!-- Create Post Card -->
            @auth
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        @if(Auth::user()->companies->isNotEmpty())
                            <div class="mb-3 d-flex align-items-center gap-2 pb-2 border-bottom">
                                <label class="form-label mb-0 small fw-semibold text-secondary"><i class="fa-solid fa-user-check me-1"></i> Post Identity:</label>
                                <select name="company_id" class="form-select form-select-sm w-auto rounded-pill border-primary-subtle bg-white fw-medium">
                                    <option value="">👤 Post as Yourself ({{ Auth::user()->name }})</option>
                                    @foreach(Auth::user()->companies as $myCompany)
                                        <option value="{{ $myCompany->id }}" {{ request('company_id') == $myCompany->id ? 'selected' : '' }}>🏢 Post as {{ $myCompany->name }} (Company)</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="d-flex gap-3 mb-3">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold fs-4 flex-shrink-0" style="width: 48px; height: 48px;">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <textarea name="content" class="form-control border-0 bg-light rounded-3 p-3" rows="3" required placeholder="Share a professional update, milestone, or announcement..."></textarea>
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                            <input type="file" name="image" class="form-control form-control-sm w-auto" accept="image/*">
                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm"><i class="fa-solid fa-paper-plane me-1"></i> Publish Post</button>
                        </div>
                    </form>
                </div>
            </div>
            @endauth

            <!-- Posts List -->
            <div class="d-flex flex-column gap-4">
                @forelse($posts as $post)
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <!-- Post Author Header -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center gap-3">
                                @if($post->company)
                                    @if($post->company->logo)
                                        <img src="{{ Storage::url($post->company->logo) }}" alt="{{ $post->company->name }}" class="rounded-circle object-fit-cover border shadow-sm" style="width: 48px; height: 48px;">
                                    @else
                                        <div class="rounded-circle text-white d-flex align-items-center justify-content-center fw-bold fs-4 shadow-sm" style="width: 48px; height: 48px; background-color: var(--brand-primary);">
                                            {{ strtoupper(substr($post->company->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <h6 class="fw-bold mb-0">
                                            <a href="{{ route('companies.show', $post->company->slug) }}" class="text-decoration-none text-dark hover-primary">{{ $post->company->name }}</a>
                                            <span class="badge bg-primary-subtle text-primary border border-primary small ms-1" style="font-size: 0.68rem;"><i class="fa-solid fa-building me-1"></i> Company</span>
                                        </h6>
                                        <span class="text-muted small">
                                            Posted by <a href="{{ route('portfolio.show', $post->user->username) }}" class="text-secondary text-decoration-none">{{ $post->user->name }}</a> &bull; {{ $post->created_at->diffForHumans() }}
                                            @if($post->histories->isNotEmpty())
                                                <button type="button" class="btn btn-link p-0 text-info text-decoration-none small ms-1 fw-semibold" style="font-size: 0.72rem;" data-bs-toggle="modal" data-bs-target="#historyModal-{{ $post->id }}">
                                                    <i class="fa-solid fa-clock-rotate-left me-1"></i> (Edited)
                                                </button>
                                            @endif
                                        </span>
                                    </div>
                                @else
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold fs-4" style="width: 48px; height: 48px;">
                                        {{ strtoupper(substr($post->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-0">
                                            <a href="{{ route('portfolio.show', $post->user->username) }}" class="text-decoration-none text-dark">{{ $post->user->name }}</a>
                                        </h6>
                                        <span class="text-muted small">
                                            {{ $post->user->portfolio->position ?? 'Professional' }} &bull; {{ $post->created_at->diffForHumans() }}
                                            @if($post->histories->isNotEmpty())
                                                <button type="button" class="btn btn-link p-0 text-info text-decoration-none small ms-1 fw-semibold" style="font-size: 0.72rem;" data-bs-toggle="modal" data-bs-target="#historyModal-{{ $post->id }}">
                                                    <i class="fa-solid fa-clock-rotate-left me-1"></i> (Edited)
                                                </button>
                                            @endif
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-light text-dark border">{{ ucfirst(str_replace('_', ' ', $post->post_type)) }}</span>
                                @auth
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light border-0 rounded-circle d-inline-flex align-items-center justify-content-center text-secondary shadow-sm" style="width: 32px; height: 32px;" type="button" id="postMenu-{{ $post->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis-vertical" style="pointer-events: none;"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 py-2" aria-labelledby="postMenu-{{ $post->id }}">
                                        @if(Auth::id() === $post->user_id || Auth::user()->isAdmin())
                                            <li>
                                                <button type="button" class="dropdown-item py-2 small" data-bs-toggle="modal" data-bs-target="#editPostModal-{{ $post->id }}">
                                                    <i class="fa-solid fa-pen me-2 text-primary"></i> Edit Post
                                                </button>
                                            </li>
                                            @if($post->histories->isNotEmpty())
                                            <li>
                                                <button type="button" class="dropdown-item py-2 small" data-bs-toggle="modal" data-bs-target="#historyModal-{{ $post->id }}">
                                                    <i class="fa-solid fa-clock-rotate-left me-2 text-info"></i> Edit History ({{ $post->histories->count() }})
                                                </button>
                                            </li>
                                            @endif
                                            <li><hr class="dropdown-divider my-1"></li>
                                            <li>
                                                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item py-2 small text-danger">
                                                        <i class="fa-solid fa-trash me-2"></i> Delete Post
                                                    </button>
                                                </form>
                                            </li>
                                        @else
                                            <li>
                                                <button type="button" class="dropdown-item py-2 small" onclick="navigator.clipboard.writeText('{{ route('feed.index') }}#post-{{ $post->id }}'); alert('Post link copied to clipboard!');">
                                                    <i class="fa-solid fa-link me-2 text-secondary"></i> Copy Post Link
                                                </button>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                                @endauth
                            </div>
                        </div>

                        <!-- Post Content (Commentary if Reshare) -->
                        @if($post->content)
                            <p class="text-secondary lh-base mb-3">{!! nl2br(e($post->content)) !!}</p>
                        @endif

                        <!-- Attached Image if any -->
                        @if($post->image_path)
                            <div class="mb-3 rounded-3 overflow-hidden">
                                <img src="{{ asset('storage/' . $post->image_path) }}" alt="Post image" class="w-100 object-fit-cover" style="max-height: 400px;">
                            </div>
                        @endif

                        <!-- Embedded Reshared Post Card if original_post_id -->
                        @if($post->originalPost)
                        <div class="card border rounded-3 bg-light p-3 mb-3 shadow-none">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                @if($post->originalPost->company)
                                    <strong class="text-dark small"><i class="fa-solid fa-building text-primary me-1"></i> {{ $post->originalPost->company->name }}</strong>
                                @else
                                    <strong class="text-dark small"><i class="fa-solid fa-user text-primary me-1"></i> {{ $post->originalPost->user->name }}</strong>
                                @endif
                                <span class="text-muted" style="font-size: 0.75rem;">&bull; {{ $post->originalPost->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-secondary small mb-2">{!! nl2br(e($post->originalPost->content)) !!}</p>
                            @if($post->originalPost->image_path)
                                <div class="rounded-2 overflow-hidden mb-2">
                                    <img src="{{ asset('storage/' . $post->originalPost->image_path) }}" alt="Original image" class="w-100 object-fit-cover" style="max-height: 250px;">
                                </div>
                            @endif
                        </div>
                        @endif

                        <!-- Attached Opportunity Card if Job Share -->
                        @if($post->opportunity)
                        <div class="card border border-primary-subtle rounded-3 bg-white p-3 mb-3 shadow-xs">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <span class="badge bg-primary-subtle text-primary border border-primary mb-1" style="font-size: 0.68rem;"><i class="fa-solid fa-briefcase me-1"></i> Shared Opportunity</span>
                                    <h6 class="fw-bold mb-0">
                                        <a href="{{ route('opportunities.show', $post->opportunity->slug) }}" class="text-decoration-none text-dark hover-primary">{{ $post->opportunity->title }}</a>
                                    </h6>
                                    <span class="text-muted small">
                                        {{ $post->opportunity->company->name ?? 'Platform Opportunity' }} &bull; <i class="fa-solid fa-location-dot text-danger"></i> {{ ucfirst($post->opportunity->location_type) }} ({{ $post->opportunity->city ?? 'Global' }})
                                    </span>
                                </div>
                                @if($post->opportunity->application_deadline)
                                    @php $b = $post->opportunity->deadline_badge; @endphp
                                    <span class="badge {{ $b['class'] }} rounded-pill ms-2 flex-shrink-0" style="font-size: 0.68rem;">
                                        <i class="{{ $b['icon'] }} me-1"></i> {{ $b['short_label'] }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-secondary small mb-3">{{ Str::limit(strip_tags($post->opportunity->description), 130) }}</p>
                            <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                @php
                                    $oppSym = ($post->opportunity->salary_currency === 'PKR' || $post->opportunity->salary_currency === 'Rs') ? 'PKR ' : '$';
                                @endphp
                                <span class="fw-bold text-dark small">{{ $post->opportunity->salary_min ? $oppSym . number_format($post->opportunity->salary_min) . ' / ' . $post->opportunity->salary_period : 'Competitive Salary' }}</span>
                                <a href="{{ route('opportunities.show', $post->opportunity->slug) }}" class="btn btn-sm btn-primary rounded-pill px-3 fw-semibold shadow-xs">
                                    <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> View & Apply
                                </a>
                            </div>
                        </div>
                        @endif

                        <!-- Action Bar: Like, Comment, Reshare -->
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top gap-2 flex-wrap">
                            @auth
                            <form action="{{ route('posts.like', $post->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-{{ $post->isLikedBy(Auth::user()) ? 'primary' : 'outline-secondary' }} rounded-pill px-3">
                                    <i class="fa-solid fa-thumbs-up me-1"></i> Like ({{ $post->likes_count }})
                                </button>
                            </form>

                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#reshareModal-{{ $post->id }}">
                                <i class="fa-solid fa-retweet me-1 text-success"></i> Reshare ({{ $post->shares_count }})
                            </button>
                            @else
                            <span class="text-muted small"><i class="fa-solid fa-thumbs-up me-1"></i> {{ $post->likes_count }} Likes</span>
                            <span class="text-muted small"><i class="fa-solid fa-retweet me-1"></i> {{ $post->shares_count }} Reshares</span>
                            @endauth

                            <span class="text-muted small"><i class="fa-solid fa-comments me-1"></i> {{ $post->comments_count }} Comments</span>
                        </div>

                        <!-- Edit Post Modal -->
                        @if(Auth::check() && (Auth::id() === $post->user_id || Auth::user()->isAdmin()))
                        <div class="modal fade" id="editPostModal-{{ $post->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg rounded-4">
                                    <div class="modal-header border-bottom py-3">
                                        <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-pen text-primary me-2"></i> Edit Post</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body p-4">
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold small">Post Content</label>
                                                <textarea name="content" class="form-control" rows="4" required>{{ old('content', $post->content) }}</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold small">Update Image (Optional)</label>
                                                <input type="file" name="image" class="form-control form-control-sm" accept="image/*">
                                                @if($post->image_path)
                                                    <div class="mt-2 text-muted small">Current image: <a href="{{ asset('storage/' . $post->image_path) }}" target="_blank">View image</a></div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="modal-footer border-top py-3">
                                            <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm"><i class="fa-solid fa-floppy-disk me-1"></i> Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Edit History Modal -->
                        @if($post->histories->isNotEmpty())
                        <div class="modal fade" id="historyModal-{{ $post->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content border-0 shadow-lg rounded-4">
                                    <div class="modal-header border-bottom py-3">
                                        <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-clock-rotate-left text-info me-2"></i> Post Edit History</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body p-4">
                                        <div class="d-flex flex-column gap-3">
                                            @foreach($post->histories as $history)
                                            <div class="p-3 bg-light rounded-3 border-start border-4 border-info">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span class="fw-semibold text-dark small"><i class="fa-solid fa-user me-1 text-primary"></i> Edited by {{ $history->user->name }}</span>
                                                    <span class="text-muted small"><i class="fa-solid fa-clock me-1"></i> {{ $history->created_at->format('M d, Y • h:i A') }} ({{ $history->created_at->diffForHumans() }})</span>
                                                </div>
                                                <div class="p-3 bg-white rounded-2 border text-secondary small mb-1">
                                                    <strong class="text-dark d-block mb-1">Previous Content:</strong>
                                                    <p class="mb-0">{!! nl2br(e($history->previous_content)) !!}</p>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="modal-footer border-top py-3">
                                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Reshare Modal -->
                        @auth
                        <div class="modal fade" id="reshareModal-{{ $post->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg rounded-4">
                                    <div class="modal-header border-bottom py-3">
                                        <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-retweet text-success me-2"></i> Reshare Post</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('posts.reshare', $post->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body p-4">
                                            @if(Auth::user()->companies->isNotEmpty())
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold small">Post Identity</label>
                                                    <select name="company_id" class="form-select form-select-sm rounded-pill">
                                                        <option value="">👤 Reshare as Yourself ({{ Auth::user()->name }})</option>
                                                        @foreach(Auth::user()->companies as $myCompany)
                                                            <option value="{{ $myCompany->id }}">🏢 Reshare as {{ $myCompany->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endif

                                            <div class="mb-3">
                                                <label class="form-label fw-semibold small">Add Your Thoughts (Optional)</label>
                                                <textarea name="content" class="form-control" rows="3" placeholder="What are your thoughts on this?"></textarea>
                                            </div>

                                            <!-- Original Post Preview -->
                                            <div class="p-3 bg-light rounded-3 border">
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    <strong class="text-dark small">{{ $post->company->name ?? $post->user->name }}</strong>
                                                    <span class="text-muted" style="font-size: 0.72rem;">&bull; {{ $post->created_at->diffForHumans() }}</span>
                                                </div>
                                                <p class="text-secondary small mb-0">{{ Str::limit($post->content, 180) }}</p>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-top py-3">
                                            <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm"><i class="fa-solid fa-retweet me-1"></i> Reshare Now</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endauth

                        <!-- Comments & Nested Replies Section -->
                        <div class="mt-4 pt-3 border-top">
                            <h6 class="fw-bold text-dark mb-3 small"><i class="fa-solid fa-comments text-primary me-2"></i> Comments ({{ $post->comments_count }})</h6>

                            <!-- Top-Level Comments List -->
                            @if($post->comments->isNotEmpty())
                            <div class="d-flex flex-column gap-3 mb-3">
                                @foreach($post->comments as $comment)
                                <div class="p-3 bg-light rounded-3">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <div class="d-flex align-items-center gap-2">
                                            <strong class="text-dark small"><a href="{{ route('portfolio.show', $comment->user->username) }}" class="text-dark text-decoration-none">{{ $comment->user->name }}</a></strong>
                                            <span class="text-muted" style="font-size: 0.72rem;">{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                    <p class="text-secondary small mb-2">{!! nl2br(e($comment->comment)) !!}</p>

                                    <!-- Comment Action Bar: Like & Reply Toggle -->
                                    <div class="d-flex align-items-center gap-3">
                                        @auth
                                        <form action="{{ route('comments.like', $comment->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-link p-0 text-decoration-none small {{ $comment->isLikedBy(Auth::user()) ? 'text-primary fw-bold' : 'text-muted' }}" style="font-size: 0.78rem;">
                                                <i class="fa-solid fa-heart me-1"></i> Like ({{ $comment->likes_count }})
                                            </button>
                                        </form>

                                        <button type="button" class="btn btn-link p-0 text-decoration-none small text-muted" style="font-size: 0.78rem;" data-bs-toggle="collapse" data-bs-target="#reply-form-{{ $comment->id }}">
                                            <i class="fa-solid fa-reply me-1"></i> Reply
                                        </button>
                                        @else
                                        <span class="text-muted small" style="font-size: 0.78rem;"><i class="fa-solid fa-heart me-1"></i> {{ $comment->likes_count }} Likes</span>
                                        @endauth
                                    </div>

                                    <!-- Inline Reply Form Collapse -->
                                    @auth
                                    <div class="collapse mt-2" id="reply-form-{{ $comment->id }}">
                                        <form action="{{ route('posts.comment', $post->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                            <div class="input-group input-group-sm">
                                                <input type="text" name="comment" class="form-control" placeholder="Write a reply to {{ $comment->user->name }}..." required>
                                                <button type="submit" class="btn btn-primary">Reply</button>
                                            </div>
                                        </form>
                                    </div>
                                    @endauth

                                    <!-- Nested Replies List -->
                                    @if($comment->replies->isNotEmpty())
                                    <div class="mt-3 ps-3 border-start border-2 border-primary-subtle d-flex flex-column gap-2">
                                        @foreach($comment->replies as $reply)
                                        <div class="p-2 bg-white rounded-2 shadow-sm">
                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                <strong class="text-dark small" style="font-size: 0.8125rem;"><a href="{{ route('portfolio.show', $reply->user->username) }}" class="text-dark text-decoration-none">{{ $reply->user->name }}</a></strong>
                                                <span class="text-muted" style="font-size: 0.7rem;">{{ $reply->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-secondary small mb-1" style="font-size: 0.8125rem;">{!! nl2br(e($reply->comment)) !!}</p>
                                            
                                            @auth
                                            <form action="{{ route('comments.like', $reply->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-link p-0 text-decoration-none small {{ $reply->isLikedBy(Auth::user()) ? 'text-primary fw-bold' : 'text-muted' }}" style="font-size: 0.72rem;">
                                                    <i class="fa-solid fa-heart me-1"></i> Like ({{ $reply->likes_count }})
                                                </button>
                                            </form>
                                            @else
                                            <span class="text-muted" style="font-size: 0.72rem;"><i class="fa-solid fa-heart me-1"></i> {{ $reply->likes_count }} Likes</span>
                                            @endauth
                                        </div>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                            @endif

                            <!-- New Main Comment Input -->
                            @auth
                            <form action="{{ route('posts.comment', $post->id) }}" method="POST" class="mt-3">
                                @csrf
                                <div class="input-group">
                                    <input type="text" name="comment" class="form-control form-control-sm" placeholder="Write a comment on this post..." required>
                                    <button type="submit" class="btn btn-sm btn-primary px-3 fw-bold">Comment</button>
                                </div>
                            </form>
                            @endauth
                        </div>
                    </div>
                </div>
                @empty
                <div class="card border-0 shadow-sm rounded-4 text-center py-5">
                    <div class="card-body">
                        <i class="fa-solid fa-comments fa-3x text-muted mb-3"></i>
                        <h4 class="fw-bold text-dark">No Posts Found</h4>
                        <p class="text-muted">Be the first to share an update on the professional feed!</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection
