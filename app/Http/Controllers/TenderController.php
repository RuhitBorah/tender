<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TenderController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Eager load relationships for display
        $query = \App\Models\Tender::with(['district', 'subdistrict'])
                    ->byUserAccess($user)
                    ->latest();

        // Simple search
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Server-side pagination (15 items per page)
        return response()->json($query->paginate(15));
    }

    public function store(Request $request)
    {
        $user = $request->user();

        // Validation
        $request->validate([
            'tender_no' => 'nullable|string|unique:tenders,tender_no',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'department' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'publish_date' => 'nullable|date',
            'closing_date' => 'nullable|date|after_or_equal:publish_date',
            'value' => 'nullable|numeric|min:0',
            'status' => 'required|in:draft,active,closed',
        ]);

        // Auto-assign location based on user's assignment
        // If Admin, they must specify district/subdistrict (for simplicity, let's assume admin creates as "System" or select district - logic can be added. 
        // For now, let's assume admin creates for a specific district passed in request, or defaults to NULL if not handled.
        // Actually adhering to "User" flow:
        
        $districtId = $user->district_id;
        $subdistrictId = $user->subdistrict_id;

        if ($user->role === 'admin') {
            // Admin can specify location
            $request->validate([
                'district_id' => 'required|exists:districts,id',
                'subdistrict_id' => 'nullable|exists:subdistricts,id',
            ]);
            $districtId = $request->input('district_id');
            $subdistrictId = $request->input('subdistrict_id');
            
            // Validation: If district has subdistricts, subdistrict is required
            $district = \App\Models\District::withCount('subdistricts')->find($districtId);
            if ($district && $district->subdistricts_count > 0 && !$subdistrictId) {
                return response()->json([
                    'error' => 'This district has subdistricts. Please select a specific subdistrict.',
                    'message' => 'This district has subdistricts. Please select a specific subdistrict.'
                ], 422);
            }
        }

        if (!$districtId && $user->role !== 'admin') {
             return response()->json(['error' => 'User has no assigned district.'], 403);
        }

        // Auto-generate tender_no if not provided
        $tenderNo = $request->input('tender_no');
        if (!$tenderNo) {
            $lastTender = \App\Models\Tender::latest('id')->first();
            $nextId = $lastTender ? $lastTender->id + 1 : 1;
            $tenderNo = 'TND-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
        }

        $tender = \App\Models\Tender::create([
            'tender_no' => $tenderNo,
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'department' => $request->input('department'),
            'state' => $request->input('state', 'Assam'),
            'publish_date' => $request->input('publish_date'),
            'closing_date' => $request->input('closing_date'),
            'value' => $request->input('value'),
            'status' => $request->input('status'),
            'district_id' => $districtId,
            'subdistrict_id' => $subdistrictId,
        ]);

        return response()->json($tender, 201);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        $tender = \App\Models\Tender::byUserAccess($user)->findOrFail($id);

        $request->validate([
            'tender_no' => 'sometimes|string|unique:tenders,tender_no,' . $id,
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'department' => 'sometimes|nullable|string|max:255',
            'state' => 'sometimes|nullable|string|max:255',
            'publish_date' => 'sometimes|nullable|date',
            'closing_date' => 'sometimes|nullable|date|after_or_equal:publish_date',
            'value' => 'sometimes|nullable|numeric|min:0',
            'status' => 'sometimes|required|in:draft,active,closed',
        ]);

        $tender->update($request->only([
            'tender_no', 'title', 'description', 'department', 'state',
            'publish_date', 'closing_date', 'value', 'status'
        ]));

        return response()->json($tender);
    }

    // Admin-specific methods
    public function adminIndex()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        return view('admin.tenders');
    }

    public function create()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $districts = \App\Models\District::with('subdistricts')->orderBy('name')->get();

        return view('admin.tenders.create', compact('districts'));
    }

    public function adminGetAll(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        // Admin sees ALL tenders without access filtering
        $query = \App\Models\Tender::with(['district', 'subdistrict'])->latest();

        // Search
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by district
        if ($request->has('district_id') && $request->district_id !== '') {
            $query->where('district_id', $request->district_id);
        }

        // Filter by subdistrict
        if ($request->has('subdistrict_id') && $request->subdistrict_id !== '') {
            $query->where('subdistrict_id', $request->subdistrict_id);
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by department
        if ($request->has('department') && $request->department !== '') {
            $query->where('department', $request->department);
        }

        // Filter by publish date range
        if ($request->has('publish_from') && $request->publish_from !== '') {
            $query->whereDate('publish_date', '>=', $request->publish_from);
        }
        if ($request->has('publish_to') && $request->publish_to !== '') {
            $query->whereDate('publish_date', '<=', $request->publish_to);
        }

        // Filter by closing date range
        if ($request->has('closing_from') && $request->closing_from !== '') {
            $query->whereDate('closing_date', '>=', $request->closing_from);
        }
        if ($request->has('closing_to') && $request->closing_to !== '') {
            $query->whereDate('closing_date', '<=', $request->closing_to);
        }

        // Filter by value range
        if ($request->has('value_min') && $request->value_min !== '') {
            $query->where('value', '>=', $request->value_min);
        }
        if ($request->has('value_max') && $request->value_max !== '') {
            $query->where('value', '<=', $request->value_max);
        }

        return response()->json($query->paginate(15));
    }

    public function destroy($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $tender = \App\Models\Tender::findOrFail($id);
        $tender->delete();

        return response()->json(['message' => 'Tender deleted successfully']);
    }
}
