<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Tender;
use App\Models\Subdistrict;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TendersImport;
use App\Exports\TendersExport;

class LocationExcelController extends Controller
{
    public function index(District $district)
    {
        $district->load('subdistricts');
        // Fetch tenders sorted by ID desc - PAGINATED for performance
        $tenders = Tender::where('district_id', $district->id)
            ->with('subdistrict')
            ->orderBy('id', 'desc')
            ->paginate(50); // Server-side pagination for 10M records scalability

        return view('admin.locations.excel_view', compact('district', 'tenders'));
    }

    public function update(Request $request, District $district)
    {
        $data = $request->validate([
            'tenders' => 'required|array',
            'tenders.*.id' => 'nullable|integer',
            'tenders.*.tender_no' => 'required|string',
            'tenders.*.title' => 'required|string',
            'tenders.*.description' => 'nullable|string',
            'tenders.*.subdistrict_id' => 'nullable|exists:subdistricts,id',
            'tenders.*.department' => 'nullable|string',
            'tenders.*.value' => 'nullable|numeric',
            'tenders.*.publish_date' => 'nullable|date',
            'tenders.*.closing_date' => 'nullable|date',
            'tenders.*.status' => 'required|in:draft,active,closed,awarded,archived',
            'tenders.*.deleted' => 'nullable|boolean' // Flag to mark for deletion
        ]);

        foreach ($data['tenders'] as $tenderData) {
            // Check if flagged for deletion
            if (!empty($tenderData['deleted']) && $tenderData['deleted'] == true) {
                if (!empty($tenderData['id'])) {
                    Tender::where('id', $tenderData['id'])->where('district_id', $district->id)->delete();
                }
                continue;
            }

            // Prepare attributes
            $attributes = [
                'tender_no' => $tenderData['tender_no'],
                'title' => $tenderData['title'],
                'description' => $tenderData['description'] ?? null,
                'subdistrict_id' => $tenderData['subdistrict_id'] ?? null,
                'department' => $tenderData['department'] ?? null,
                'value' => $tenderData['value'] ?? null,
                'publish_date' => $tenderData['publish_date'] ?? null,
                'closing_date' => $tenderData['closing_date'] ?? null,
                'status' => $tenderData['status'],
                'district_id' => $district->id,
            ];

            // If subdistrict is provided, verify it belongs to this district
            if ($attributes['subdistrict_id']) {
                $exists = Subdistrict::where('id', $attributes['subdistrict_id'])
                    ->where('district_id', $district->id)
                    ->exists();
                if (!$exists) {
                    $attributes['subdistrict_id'] = null; // or throw error
                }
            }

            if (!empty($tenderData['id'])) {
                // Update existing
                Tender::where('id', $tenderData['id'])
                    ->where('district_id', $district->id)
                    ->update($attributes);
            } else {
                // Create new
                Tender::create($attributes);
            }
        }

        return response()->json(['message' => 'Tenders updated successfully']);
    }

    public function export(District $district) 
    {
        return Excel::download(new TendersExport($district), 'tenders_' . $district->name . '.xlsx');
    }

    public function import(Request $request, District $district) 
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new TendersImport($district), $request->file('file'));
        
        return back()->with('success', 'Tenders imported successfully.');
    }

    // Subdistrict Methods

    public function indexSubdistrict(District $district, Subdistrict $subdistrict)
    {
        // Ensure subdistrict belongs to district
        if ($subdistrict->district_id !== $district->id) {
            abort(404);
        }

        // Fetch tenders sorted by ID desc - PAGINATED for performance
        $tenders = Tender::where('subdistrict_id', $subdistrict->id)
            ->with('subdistrict')
            ->orderBy('id', 'desc')
            ->paginate(50); // Server-side pagination for 10M records scalability

        return view('admin.locations.excel_view', compact('district', 'subdistrict', 'tenders'));
    }

    public function updateSubdistrict(Request $request, District $district, Subdistrict $subdistrict)
    {
        if ($subdistrict->district_id !== $district->id) abort(404);

        $data = $request->validate([
            'tenders' => 'required|array',
            'tenders.*.id' => 'nullable|integer',
            'tenders.*.tender_no' => 'required|string',
            'tenders.*.title' => 'required|string',
            'tenders.*.description' => 'nullable|string',
            // subdistrict_id is implied/fixed
            'tenders.*.department' => 'nullable|string',
            'tenders.*.value' => 'nullable|numeric',
            'tenders.*.publish_date' => 'nullable|date',
            'tenders.*.closing_date' => 'nullable|date',
            'tenders.*.status' => 'required|in:draft,active,closed,awarded,archived',
            'tenders.*.deleted' => 'nullable|boolean'
        ]);

        foreach ($data['tenders'] as $tenderData) {
            if (!empty($tenderData['deleted']) && $tenderData['deleted'] == true) {
                if (!empty($tenderData['id'])) {
                    Tender::where('id', $tenderData['id'])
                        ->where('subdistrict_id', $subdistrict->id)
                        ->delete();
                }
                continue;
            }

            $attributes = [
                'tender_no' => $tenderData['tender_no'],
                'title' => $tenderData['title'],
                'description' => $tenderData['description'] ?? null,
                'subdistrict_id' => $subdistrict->id, // Force subdistrict
                'department' => $tenderData['department'] ?? null,
                'value' => $tenderData['value'] ?? null,
                'publish_date' => $tenderData['publish_date'] ?? null,
                'closing_date' => $tenderData['closing_date'] ?? null,
                'status' => $tenderData['status'],
                'district_id' => $district->id,
            ];

            if (!empty($tenderData['id'])) {
                Tender::where('id', $tenderData['id'])
                    ->where('subdistrict_id', $subdistrict->id)
                    ->update($attributes);
            } else {
                Tender::create($attributes);
            }
        }

        return response()->json(['message' => 'Tenders updated successfully']);
    }

    public function exportSubdistrict(District $district, Subdistrict $subdistrict)
    {
        if ($subdistrict->district_id !== $district->id) abort(404);
        return Excel::download(new TendersExport($district, $subdistrict), 'tenders_' . $subdistrict->name . '.xlsx');
    }

    public function importSubdistrict(Request $request, District $district, Subdistrict $subdistrict)
    {
        if ($subdistrict->district_id !== $district->id) abort(404);

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(new TendersImport($district, $subdistrict), $request->file('file'));
        
        return back()->with('success', 'Tenders imported successfully.');
    }
}
