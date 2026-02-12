<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subdistrict;
use Illuminate\Http\Request;

class SubdistrictController extends Controller
{
    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'district_id' => 'required|exists:districts,id',
        ]);

        $subdistrict = Subdistrict::create($validated);
        $subdistrict->load('district');

        return response()->json($subdistrict, 201);
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $subdistrict = Subdistrict::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'district_id' => 'required|exists:districts,id',
        ]);

        $subdistrict->update($validated);
        $subdistrict->load('district');

        return response()->json($subdistrict);
    }

    public function destroy($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $subdistrict = Subdistrict::withCount(['users', 'tenders'])->findOrFail($id);

        // Prevent deletion if subdistrict has related data
        if ($subdistrict->users_count > 0 || $subdistrict->tenders_count > 0) {
            return response()->json([
                'message' => 'Cannot delete subdistrict. It has ' . $subdistrict->users_count . ' users and ' . $subdistrict->tenders_count . ' tenders.'
            ], 422);
        }

        $subdistrict->delete();

        return response()->json(['message' => 'Subdistrict deleted successfully']);
    }
}
