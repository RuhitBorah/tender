<?php

namespace App\Exports;

use App\Models\District;
use App\Models\Tender;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TendersExport implements FromQuery, WithHeadings, WithMapping
{
    protected $district;
    protected $subdistrict;

    public function __construct(District $district, $subdistrict = null)
    {
        $this->district = $district;
        $this->subdistrict = $subdistrict;
    }

    public function query()
    {
        $query = Tender::query()->where('district_id', $this->district->id);
        
        if ($this->subdistrict) {
            $query->where('subdistrict_id', $this->subdistrict->id);
        }

        return $query->with('subdistrict');
    }

    public function headings(): array
    {
        return [
            'ID',
            'Tender No',
            'Title',
            'Description',
            'District Name',
            'Subdistrict',
            'Department',
            'Amount',
            'Publish Date',
            'Closing Date',
            'Status',
        ];
    }

    public function map($tender): array
    {
        return [
            $tender->id,
            $tender->tender_no,
            $tender->title,
            $tender->description,
            $this->district->name,
            $tender->subdistrict ? $tender->subdistrict->name : '',
            $tender->department,
            $tender->value,
            $tender->publish_date ? $tender->publish_date->format('Y-m-d') : '',
            $tender->closing_date ? $tender->closing_date->format('Y-m-d') : '',
            $tender->status,
        ];
    }
}
