<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index($username)
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Ensure the username in the URL matches the authenticated user's name
        if (Str::slug($user->name) !== $username) {
            abort(403, 'Unauthorized access to this dashboard.');
        }

        // Eager load district and tenders
        $user->load(['district', 'district.tenders']);
        
        // Strict Isolation Logic (same as UserTenderController)
        $query = Tender::query()->with('subdistrict');

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

        // Calculate Stats
        $stats = [
            'active' => (clone $query)->where('status', 'active')->count(),
            'active_value' => (clone $query)->where('status', 'active')->sum('value'),
            'closed' => (clone $query)->where('status', 'closed')->count(),
            'closed_value' => (clone $query)->where('status', 'closed')->sum('value'),
        ];

        $tenders = $query->latest()->take(5)->get(); // Get top 5 recent

        return view('dashboard', [
            'user' => $user,
            'tenders' => $tenders,
            'stats' => $stats
        ]);
    }
}
