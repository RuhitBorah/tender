<?php

namespace App\Imports;

use App\Models\Tender;
use App\Models\Subdistrict;
use App\Models\District;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Validation\Rule;

class TendersImport implements ToModel, WithHeadingRow, WithValidation
{
    protected $district;
    protected $subdistricts;
    protected $targetSubdistrict;

    public function __construct(District $district, $targetSubdistrict = null)
    {
        $this->district = $district;
        $this->targetSubdistrict = $targetSubdistrict;
        $this->subdistricts = $district->subdistricts->pluck('id', 'name')->mapWithKeys(function ($item, $key) {
            return [strtolower($key) => $item];
        });
    }

    public function model(array $row)
    {
        $subdistrictId = null;
        
        if ($this->targetSubdistrict) {
            $subdistrictId = $this->targetSubdistrict->id;
        } else {
            // Check for 'subdistrict_name' or 'subdistrict'
            $nameRaw = $row['subdistrict_name'] ?? $row['subdistrict'] ?? null;
            
            if (!empty($nameRaw)) {
                $name = strtolower(trim($nameRaw));
                $subdistrictId = $this->subdistricts[$name] ?? null;
            }
        }

        // If 'id' is present and belongs to district, update. Else create.
        $tender = Tender::updateOrCreate(
            [
                'tender_no' => $row['tender_no'],
                'district_id' => $this->district->id
            ],
            [
                'title' => $row['title'],
                'description' => $row['description'] ?? null,
                'subdistrict_id' => $subdistrictId,
                'department' => $row['department'] ?? null,
                'value' => $row['amount'] ?? $row['value'] ?? null,
                'publish_date' => $this->transformDate($row['publish_date'] ?? null),
                'closing_date' => $this->transformDate($row['closing_date'] ?? null),
                'status' => strtolower($row['status'] ?? 'draft'),
            ]
        );

        return $tender;
    }

    public function rules(): array
    {
        return [
            'tender_no' => 'required',
            'title' => 'required',
            'status' => 'required|in:draft,active,closed,awarded,archived',
        ];
    }

    private function transformDate($value)
    {
        if (!$value) return null;
        
        try {
            if (is_numeric($value)) {
                return Date::excelToDateTimeObject($value);
            }
            return \Carbon\Carbon::parse($value);
        } catch (\Exception $e) {
            return null;
        }
    }
}
