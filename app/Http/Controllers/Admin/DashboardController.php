<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $stats = [
            'total_users' => \App\Models\User::count(),
            'admin_users' => \App\Models\User::where('role', 'admin')->count(),
            'regular_users' => \App\Models\User::where('role', 'user')->count(),
            'total_tenders' => \App\Models\Tender::count(),
            'draft_tenders' => \App\Models\Tender::where('status', 'draft')->count(),
            'active_tenders' => \App\Models\Tender::where('status', 'active')->count(),
            'closed_tenders' => \App\Models\Tender::where('status', 'closed')->count(),
            'total_districts' => \App\Models\District::count(),
            'total_subdistricts' => \App\Models\Subdistrict::count(),
            'total_states' => \App\Models\Tender::whereNotNull('state')->where('state', '!=', '')->distinct('state')->count('state'),
        ];

        // Recent tenders
        $recentTenders = \App\Models\Tender::with(['district', 'subdistrict'])
            ->latest()
            ->take(5)
            ->get();

        // Recent users
        $recentUsers = \App\Models\User::with(['district', 'subdistrict'])
            ->latest()
            ->take(5)
            ->get();

        // Tenders by district
        $tendersByDistrict = \App\Models\District::withCount('tenders')
            ->orderBy('tenders_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentTenders', 'recentUsers', 'tendersByDistrict'));
    }
}
