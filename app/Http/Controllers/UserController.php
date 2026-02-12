<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function view()
    {
        if (auth()->user()->role !== 'admin') abort(403);

        $stats = [
            'total_users' => \App\Models\User::count(),
            'admin_users' => \App\Models\User::where('role', 'admin')->count(),
            'regular_users' => \App\Models\User::where('role', 'user')->count(),
        ];

        return view('admin.users', compact('stats'));
    }

    public function index(Request $request)
    {
        // Admin only - enforced by middleware in route, but good to be safe
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $query = \App\Models\User::with(['district', 'subdistrict'])
                    ->latest();

        if ($request->has('district_id')) {
            $query->where('district_id', $request->district_id);
        }

        if ($request->has('subdistrict_id')) {
            $query->where('subdistrict_id', $request->subdistrict_id);
        }

        $users = $query->paginate(15);
        
        return response()->json($users); // API for grid
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') abort(403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role' => 'required|in:admin,user',
            'district_id' => 'nullable|exists:districts,id',
            'subdistrict_id' => 'nullable|exists:subdistricts,id',
        ]);

        $validated['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);

        $user = \App\Models\User::create($validated);

        return response()->json($user, 201);
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') abort(403);

        $user = \App\Models\User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'role' => 'required|in:admin,user',
            'district_id' => 'nullable|exists:districts,id', // Can be null if admin or not assigned
            'subdistrict_id' => 'nullable|exists:subdistricts,id',
            'password' => 'nullable|min:8' // Only update if provided
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);
        
        return response()->json($user);
    }

    public function destroy($id)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        
        $user = \App\Models\User::findOrFail($id);
        if ($user->id === auth()->id()) {
            return response()->json(['error' => 'Cannot delete yourself'], 400);
        }
        
        $user->delete();
        return response()->json(['message' => 'User deleted']);
    }

    public function rejectDeletion($id)
    {
        if (auth()->user()->role !== 'admin') abort(403);
        
        $user = \App\Models\User::findOrFail($id);
        $user->update(['deletion_requested_at' => null]);
        
        return response()->json(['message' => 'Deletion request rejected']);
    }
}
