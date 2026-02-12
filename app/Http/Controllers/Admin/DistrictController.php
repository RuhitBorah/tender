<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        return view('admin.locations');
    }

    public function getAll()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $districts = District::withCount(['subdistricts', 'users', 'tenders'])
            ->with(['subdistricts' => function($query) {
                $query->withCount(['users', 'tenders']);
            }])
            ->orderBy('name')
            ->get();

        return response()->json($districts);
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:districts,name',
        ]);

        $district = District::create($validated);

        return response()->json($district, 201);
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $district = District::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:districts,name,' . $id,
        ]);

        $district->update($validated);

        return response()->json($district);
    }

    public function destroy($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $district = District::withCount(['subdistricts', 'users', 'tenders'])->findOrFail($id);

        // Prevent deletion if district has related data
        if ($district->subdistricts_count > 0 || $district->users_count > 0 || $district->tenders_count > 0) {
            return response()->json([
                'message' => 'Cannot delete district. It has ' . $district->subdistricts_count . ' subdistricts, ' . $district->users_count . ' users, and ' . $district->tenders_count . ' tenders.'
            ], 422);
        }

        $district->delete();

        return response()->json(['message' => 'District deleted successfully']);
    }
}
