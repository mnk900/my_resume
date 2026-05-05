<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Portfolio;
use App\Mail\PortfolioMessageReply;
use App\Mail\PortfolioMessageNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Store a new message from the public portfolio.
     */
    public function store(Request $request, Portfolio $portfolio)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        $portfolio->messages()->create([
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message,
            'subject' => 'Message from Portfolio'
        ]);

        // Send notification to portfolio owner
        Mail::to($portfolio->user->email)->send(new PortfolioMessageNotification(
            $request->name,
            $request->email,
            $request->message
        ));

        return back()->with('status', 'message-sent');
    }

    /**
     * Reply to a message.
     */
    public function reply(Request $request, Message $message)
    {
        // Security check
        if ($message->portfolio->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'reply' => 'required|string'
        ]);

        // Update message
        $message->update([
            'reply' => $request->reply,
            'is_read' => true
        ]);

        // Send Email
        Mail::to($message->email)->send(new PortfolioMessageReply(
            $request->reply,
            Auth::user()->name,
            $message->message
        ));

        return back()->with('status', 'reply-sent');
    }

    /**
     * Mark message as read.
     */
    public function markAsRead(Message $message)
    {
        if ($message->portfolio->user_id !== Auth::id()) {
            abort(403);
        }

        $message->update(['is_read' => true]);
        return back();
    }

    /**
     * Delete message.
     */
    public function destroy(Message $message)
    {
        if ($message->portfolio->user_id !== Auth::id()) {
            abort(403);
        }

        $message->delete();
        return back()->with('status', 'message-deleted');
    }
}
