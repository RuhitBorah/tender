<?php

namespace App\Http\Controllers;

use App\Models\Tender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserTenderController extends Controller
{
    public function index($username)
    {
        $user = Auth::user();

        // Ensure the username in the URL matches the authenticated user's name
        if (Str::slug($user->name) !== $username) {
            abort(403, 'Unauthorized access to this page.');
        }

        // Get tenders based on user's access level (Manual filter to ensure isolation)
        $query = \App\Models\Tender::query()->with('subdistrict');

        if ($user->role !== 'admin') {
            if ($user->subdistrict_id) {
                $query->where('subdistrict_id', $user->subdistrict_id);
            } elseif ($user->district_id) {
                // STRICT ISOLATION: District users only see tenders with NO subdistrict
                $query->where('district_id', $user->district_id)
                      ->whereNull('subdistrict_id');
            } else {
                $query->whereRaw('1=0'); // No access
            }
        }

        $tenders = $query->latest()->get();

        return view('user.tenders.index', [
            'user' => $user,
            'tenders' => $tenders,
        ]);
    }

    public function update(Request $request, $username, Tender $tender)
    {
        $user = Auth::user();

        // Ensure the username in the URL matches the authenticated user's name
        if (Str::slug($user->name) !== $username) {
            abort(403, 'Unauthorized access.');
        }

        // Ensure the tender belongs to the user's scope (District or Subdistrict)
        $hasAccess = Tender::byUserAccess($user)->where('id', $tender->id)->exists();

        if (!$hasAccess) {
            abort(403, 'Unauthorized access to this tender.');
        }

        // Validate the request
        $validated = $request->validate([
            'tender_no' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'department' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'publish_date' => 'nullable|date',
            'closing_date' => 'nullable|date',
            'value' => 'required|numeric|min:0',
            'status' => 'required|in:open,closed,awarded',
        ]);

        // Update the tender
        $tender->update($validated);

        return response()->json([
            'message' => 'Tender updated successfully.',
            'tender' => $tender,
        ]);
    }
}
