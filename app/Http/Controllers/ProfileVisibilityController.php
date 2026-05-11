<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileVisibilityController extends Controller
{
    /**
     * Update the authenticated user's contact visibility flags.
     */
    public function update(Request $request)
    {
        $request->validate([
            'show_email'    => 'required|in:show,hide',
            'show_phone'    => 'required|in:show,hide',
            'show_linkedin' => 'required|in:show,hide',
        ]);

        $portfolio = Auth::user()->portfolio;
        $portfolio->show_email    = $request->input('show_email') === 'show';
        $portfolio->show_phone    = $request->input('show_phone') === 'show';
        $portfolio->show_linkedin = $request->input('show_linkedin') === 'show';
        $portfolio->save();

        return back()->with('status', 'Contact visibility updated.');
    }
}
