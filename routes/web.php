<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


Route::get('/', function () {
    return view('welcome');
});


Route::get('/u/{username}/dashboard', function ($username) {
    $user = Auth::user();
    
    $stats = [
        'active' => \App\Models\Tender::byUserAccess($user)->where('status', 'active')->count(),
        'active_value' => \App\Models\Tender::byUserAccess($user)->where('status', 'active')->sum('value'),
        'closed' => \App\Models\Tender::byUserAccess($user)->where('status', 'closed')->count(),
        'closed_value' => \App\Models\Tender::byUserAccess($user)->where('status', 'closed')->sum('value'),
    ];
    
    $tenders = \App\Models\Tender::byUserAccess($user)->latest()->take(5)->get();

    return view('dashboard', compact('user', 'stats', 'tenders'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/u/{username}/tenders', function ($username) {
    $user = Auth::user();
    $tenders = \App\Models\Tender::byUserAccess($user)->with(['district', 'subdistrict'])->latest()->get();
    return view('user.tenders.index', compact('user', 'tenders'));
})->middleware(['auth', 'verified'])->name('user.tenders');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');

    // Tender CRUD (used by inline forms at /tenders endpoint)
    Route::post('/tenders', function (Illuminate\Http\Request $request) {
        $validated = $request->validate([
            'tender_no' => 'required|string',
            'title' => 'required|string',
            'description' => 'nullable|string',
            'department' => 'nullable|string',
            'state' => 'nullable|string',
            'district_id' => 'nullable|exists:districts,id',
            'subdistrict_id' => 'nullable|exists:subdistricts,id',
            'publish_date' => 'nullable|date',
            'closing_date' => 'nullable|date',
            'value' => 'nullable|numeric',
            'status' => 'required|in:draft,active,closed,awarded,archived',
        ]);
        $tender = \App\Models\Tender::create($validated);
        return response()->json($tender);
    });

    Route::put('/tenders/{tender}', function (Illuminate\Http\Request $request, \App\Models\Tender $tender) {
        $validated = $request->validate([
            'tender_no' => 'required|string',
            'title' => 'required|string',
            'description' => 'nullable|string',
            'department' => 'nullable|string',
            'state' => 'nullable|string',
            'district_id' => 'nullable|exists:districts,id',
            'subdistrict_id' => 'nullable|exists:subdistricts,id',
            'publish_date' => 'nullable|date',
            'closing_date' => 'nullable|date',
            'value' => 'nullable|numeric',
            'status' => 'required|in:draft,active,closed,awarded,archived',
        ]);
        $tender->update($validated);
        return response()->json($tender);
    });

    // Admin Routes — guarded by role check middleware
    Route::prefix('admin')->name('admin.')->middleware(['auth', \App\Http\Middleware\EnsureUserIsAdmin::class])->group(function () {
        
        // Dashboard
        Route::get('/dashboard', function () {
            $stats = [
                'total_users' => \App\Models\User::count(),
                'active_tenders' => \App\Models\Tender::where('status', 'active')->count(),
                'total_tenders' => \App\Models\Tender::count(),
                'draft_tenders' => \App\Models\Tender::where('status', 'draft')->count(),
                'closed_tenders' => \App\Models\Tender::where('status', 'closed')->count(),
                'closed_value' => \App\Models\Tender::where('status', 'closed')->sum('value'),
                'active_value' => \App\Models\Tender::where('status', 'active')->sum('value'),
                'total_districts' => \App\Models\District::count(),
                'total_subdistricts' => \App\Models\Subdistrict::count(),
                'total_states' => 1,
            ];

            $recentTenders = \App\Models\Tender::with('district')->latest()->take(5)->get();
            $tendersByDistrict = \App\Models\District::withCount('tenders')->orderByDesc('tenders_count')->take(5)->get();
            $recentUsers = \App\Models\User::latest()->take(5)->get();

            return view('admin.dashboard', compact('stats', 'recentTenders', 'tendersByDistrict', 'recentUsers'));
        })->name('dashboard');

        // Page Views
        Route::view('/tenders', 'admin.tenders')->name('tenders');
        Route::get('/users', function () {
            $stats = [
                'total_users' => \App\Models\User::count(),
                'admin_users' => \App\Models\User::where('role', 'admin')->count(),
                'regular_users' => \App\Models\User::where('role', 'user')->count(),
            ];
            return view('admin.users', compact('stats'));
        })->name('users');
        Route::view('/locations', 'admin.locations')->name('locations');
        Route::view('/tenders/create', 'admin.tenders.create')->name('tenders.create'); // Assuming this view exists or will be created

        // Admin API Routes (internal use for Alpine.js)
        Route::prefix('api')->name('api.')->group(function () {
            
            // Tenders API
            Route::get('/tenders', function (Illuminate\Http\Request $request) {
                $query = \App\Models\Tender::with(['district', 'subdistrict'])->latest();

                if ($request->search) {
                    $search = $request->search;
                    $query->where(function ($q) use ($search) {
                        $q->where('title', 'like', "%{$search}%")
                          ->orWhere('tender_no', 'like', "%{$search}%")
                          ->orWhere('department', 'like', "%{$search}%");
                    });
                }
                if ($request->district_id) $query->where('district_id', $request->district_id);
                if ($request->subdistrict_id) $query->where('subdistrict_id', $request->subdistrict_id);
                if ($request->status) $query->where('status', $request->status);
                if ($request->department) $query->where('department', $request->department);
                if ($request->publish_from) $query->whereDate('publish_date', '>=', $request->publish_from);
                if ($request->publish_to) $query->whereDate('publish_date', '<=', $request->publish_to);
                if ($request->closing_from) $query->whereDate('closing_date', '>=', $request->closing_from);
                if ($request->closing_to) $query->whereDate('closing_date', '<=', $request->closing_to);
                if ($request->value_min) $query->where('value', '>=', $request->value_min);
                if ($request->value_max) $query->where('value', '<=', $request->value_max);

                return $query->paginate(15);
            });

            Route::post('/tenders', function (Illuminate\Http\Request $request) {
                $validated = $request->validate([
                    'tender_no' => 'required|string',
                    'title' => 'required|string',
                    'description' => 'nullable|string',
                    'department' => 'nullable|string',
                    'state' => 'nullable|string',
                    'district_id' => 'nullable|exists:districts,id',
                    'subdistrict_id' => 'nullable|exists:subdistricts,id',
                    'publish_date' => 'nullable|date',
                    'closing_date' => 'nullable|date',
                    'value' => 'nullable|numeric',
                    'status' => 'required|in:draft,active,closed,awarded,archived',
                ]);
                return \App\Models\Tender::create($validated);
            });

            Route::put('/tenders/{tender}', function (Illuminate\Http\Request $request, \App\Models\Tender $tender) {
                $validated = $request->validate([
                    'tender_no' => 'required|string',
                    'title' => 'required|string',
                    'description' => 'nullable|string',
                    'department' => 'nullable|string',
                    'state' => 'nullable|string',
                    'publish_date' => 'nullable|date',
                    'closing_date' => 'nullable|date',
                    'value' => 'nullable|numeric',
                    'status' => 'required|in:draft,active,closed',
                ]);
                $tender->update($validated);
                return $tender;
            });

            Route::delete('/tenders/{tender}', function (\App\Models\Tender $tender) {
                $tender->delete();
                return response()->json(['message' => 'Deleted']);
            });

            // Users API
            Route::get('/users', function (Illuminate\Http\Request $request) {
                $query = \App\Models\User::with(['district', 'subdistrict'])->latest();
                if ($request->search) {
                    $search = $request->search;
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                    });
                }
                return $query->paginate(15);
            });

            Route::post('/users', function (Illuminate\Http\Request $request) {
                $validated = $request->validate([
                    'name' => 'required|string|max:255',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required|string|min:6',
                    'role' => 'required|in:admin,user',
                    'district_id' => 'nullable|exists:districts,id',
                    'subdistrict_id' => 'nullable|exists:subdistricts,id',
                ]);
                $validated['password'] = bcrypt($validated['password']);
                return \App\Models\User::create($validated);
            });

            Route::put('/users/{user}', function (Illuminate\Http\Request $request, \App\Models\User $user) {
                $rules = [
                    'name' => 'required|string|max:255',
                    'email' => 'required|email|unique:users,email,' . $user->id,
                    'role' => 'required|in:admin,user',
                    'district_id' => 'nullable|exists:districts,id',
                    'subdistrict_id' => 'nullable|exists:subdistricts,id',
                ];
                if ($request->filled('password')) {
                    $rules['password'] = 'string|min:6';
                }
                $validated = $request->validate($rules);
                if (isset($validated['password'])) {
                    $validated['password'] = bcrypt($validated['password']);
                }
                $user->update($validated);
                return $user;
            });

            Route::delete('/users/{user}', function (\App\Models\User $user) {
                if ($user->role === 'admin') abort(403, 'Cannot delete admin');
                $user->delete();
                return response()->json(['message' => 'Deleted']);
            });

            Route::post('/users/{user}/reject-deletion', function (\App\Models\User $user) {
                $user->update(['deletion_requested_at' => null]);
                return response()->json(['message' => 'Rejection successful']);
            });

            // Locations API
            Route::get('/districts', function () {
                return \App\Models\District::with('subdistricts')->get();
            });

            // Alias for users page which fetches /admin/api/locations
            Route::get('/locations', function () {
                return \App\Models\District::with('subdistricts')->get();
            });

            Route::post('/districts', function (Illuminate\Http\Request $request) {
                $validated = $request->validate(['name' => 'required|string|max:255']);
                return \App\Models\District::create($validated);
            });

            Route::put('/districts/{district}', function (Illuminate\Http\Request $request, \App\Models\District $district) {
                $validated = $request->validate(['name' => 'required|string|max:255']);
                $district->update($validated);
                return $district;
            });

            Route::delete('/districts/{district}', function (\App\Models\District $district) {
                if ($district->tenders()->count() > 0) {
                    return response()->json(['message' => 'Cannot delete district with tenders'], 422);
                }
                $district->subdistricts()->delete();
                $district->delete();
                return response()->json(['message' => 'Deleted']);
            });

            Route::post('/subdistricts', function (Illuminate\Http\Request $request) {
                $validated = $request->validate([
                    'name' => 'required|string|max:255',
                    'district_id' => 'required|exists:districts,id'
                ]);
                return \App\Models\Subdistrict::create($validated);
            });

            Route::put('/subdistricts/{subdistrict}', function (Illuminate\Http\Request $request, \App\Models\Subdistrict $subdistrict) {
                $validated = $request->validate([
                    'name' => 'required|string|max:255',
                    'district_id' => 'required|exists:districts,id'
                ]);
                $subdistrict->update($validated);
                return $subdistrict;
            });

            Route::delete('/subdistricts/{subdistrict}', function (\App\Models\Subdistrict $subdistrict) {
                $subdistrict->delete();
                return response()->json(['message' => 'Deleted']);
            });
        });
    });
});

require __DIR__.'/auth.php';
