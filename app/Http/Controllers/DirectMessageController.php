<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Conversation;
use App\Models\DirectMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DirectMessageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        // Mark all unread direct messages sent to user as read on workspace load
        DirectMessage::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        if ($user->portfolio) {
            \App\Models\Message::where('portfolio_id', $user->portfolio->id)
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }

        // Fetch all conversations for auth user ordered by latest message
        $conversations = Conversation::where('user_one_id', $user->id)
            ->orWhere('user_two_id', $user->id)
            ->with(['userOne.portfolio', 'userTwo.portfolio', 'latestMessage'])
            ->orderBy('last_message_at', 'desc')
            ->get();

        $activeConversation = null;
        $messages = collect();

        if ($request->has('conversation')) {
            $activeConversation = $conversations->firstWhere('id', $request->query('conversation'));
        } elseif ($request->has('user_id')) {
            $targetUser = User::find($request->query('user_id'));
            if ($targetUser && $targetUser->id !== $user->id) {
                $activeConversation = Conversation::findOrCreateBetween($user->id, $targetUser->id);
                $activeConversation->update(['last_message_at' => now()]);
                // Re-fetch conversations to include the active/new conversation
                $conversations = Conversation::where('user_one_id', $user->id)
                    ->orWhere('user_two_id', $user->id)
                    ->with(['userOne.portfolio', 'userTwo.portfolio', 'latestMessage'])
                    ->orderBy('last_message_at', 'desc')
                    ->get();
            }
        }

        if (!$activeConversation && $conversations->isNotEmpty()) {
            $activeConversation = $conversations->first();
        }

        if ($activeConversation) {
            // Verify participant
            if ($activeConversation->user_one_id !== $user->id && $activeConversation->user_two_id !== $user->id) {
                abort(403);
            }

            // Mark unread messages sent to current user as read
            DirectMessage::where('conversation_id', $activeConversation->id)
                ->where('receiver_id', $user->id)
                ->where('is_read', false)
                ->update([
                    'is_read' => true,
                    'read_at' => now(),
                ]);

            $messages = $activeConversation->messages()
                ->with(['sender.portfolio'])
                ->orderBy('created_at', 'asc')
                ->get();
        }

        return view('messages.index', compact('conversations', 'activeConversation', 'messages'));
    }

    public function startConversation(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->withErrors('You cannot start a chat conversation with yourself.');
        }

        $conversation = Conversation::findOrCreateBetween(Auth::id(), $user->id);
        $conversation->update(['last_message_at' => now()]);

        return redirect()->route('messages.index', ['conversation' => $conversation->id]);
    }

    public function sendMessage(Request $request, Conversation $conversation)
    {
        if ($conversation->user_one_id !== Auth::id() && $conversation->user_two_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'body' => 'required|string|max:5000',
        ]);

        $otherUser = $conversation->getOtherUser(Auth::id());

        $message = DirectMessage::create([
            'conversation_id' => $conversation->id,
            'sender_id' => Auth::id(),
            'receiver_id' => $otherUser->id,
            'body' => trim($request->input('body')),
            'is_read' => false,
        ]);

        $conversation->update(['last_message_at' => now()]);

        if ($request->expectsJson()) {
            $message->load('sender.portfolio');
            return response()->json(['success' => true, 'message' => $message]);
        }

        return redirect()->route('messages.index', ['conversation' => $conversation->id]);
    }

    public function fetchMessages(Conversation $conversation)
    {
        if ($conversation->user_one_id !== Auth::id() && $conversation->user_two_id !== Auth::id()) {
            abort(403);
        }

        // Mark unread messages sent to current user as read
        DirectMessage::where('conversation_id', $conversation->id)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        $messages = $conversation->messages()
            ->with(['sender.portfolio'])
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json(['messages' => $messages]);
    }

    public function unreadCount()
    {
        return response()->json(['unread_count' => Auth::user()->unreadDirectMessagesCount()]);
    }
}
