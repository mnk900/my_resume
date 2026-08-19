<?php

namespace App\Http\Controllers;

use App\Models\ContentReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContentReportController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reportable_type' => 'required|string',
            'reportable_id' => 'required|integer',
            'reason' => 'required|string|max:255',
            'details' => 'nullable|string|max:2000',
        ]);

        ContentReport::create([
            'reporter_id' => Auth::id(),
            'reportable_type' => $validated['reportable_type'],
            'reportable_id' => $validated['reportable_id'],
            'reason' => $validated['reason'],
            'details' => $validated['details'] ?? null,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Report submitted successfully. Platform moderators will review this content.');
    }
}
