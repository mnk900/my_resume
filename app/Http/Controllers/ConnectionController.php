<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Connection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConnectionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function sendRequest(User $user)
    {
        // Don't connect with self or admin
        if ($user->id === Auth::id() || $user->role === 'admin') {
            return back()->withErrors('Invalid user connection request.');
        }

        // Check if there is already a connection
        $existing = Auth::user()->connectionWith($user);
        if ($existing) {
            return back()->withErrors('A connection request already exists or is accepted.');
        }

        Connection::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $user->id,
            'status' => 'pending',
        ]);

        return back()->with('status', 'connection-request-sent');
    }

    public function acceptRequest(Connection $connection)
    {
        // Must be the receiver to accept
        if ($connection->receiver_id !== Auth::id()) {
            abort(403);
        }

        $connection->update(['status' => 'accepted']);

        return back()->with('status', 'connection-accepted');
    }

    public function rejectRequest(Connection $connection)
    {
        // Must be the receiver to reject
        if ($connection->receiver_id !== Auth::id()) {
            abort(403);
        }

        $connection->delete();

        return back()->with('status', 'connection-ignored');
    }

    public function cancelRequest(Connection $connection)
    {
        // Must be the sender to cancel
        if ($connection->sender_id !== Auth::id()) {
            abort(403);
        }

        $connection->delete();

        return back()->with('status', 'connection-request-cancelled');
    }

    public function removeConnection(User $user)
    {
        $connection = Auth::user()->connectionWith($user);

        if (!$connection || $connection->status !== 'accepted') {
            return back()->withErrors('Connection not found.');
        }

        $connection->delete();

        return back()->with('status', 'connection-removed');
    }
}
