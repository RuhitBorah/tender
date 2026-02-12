<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tender extends Model
{
    protected $fillable = [
        'tender_no',
        'title',
        'description',
        'department',
        'state',
        'publish_date',
        'closing_date',
        'value',
        'status',
        'district_id',
        'subdistrict_id',
    ];

    protected $casts = [
        'publish_date' => 'date',
        'closing_date' => 'date',
        'value' => 'decimal:2',
    ];

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function subdistrict()
    {
        return $this->belongsTo(Subdistrict::class);
    }

    public function scopeByUserAccess($query, $user)
    {
        if ($user->role === 'admin') {
            return $query;
        }

        if ($user->subdistrict_id) {
            return $query->where('subdistrict_id', $user->subdistrict_id);
        }

        if ($user->district_id) {
            return $query->where('district_id', $user->district_id)->whereNull('subdistrict_id');
        }

        return $query->whereRaw('1 = 0'); // No access
    }
}
