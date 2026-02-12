<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $guarded = [];

    public function subdistricts()
    {
        return $this->hasMany(Subdistrict::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function tenders()
    {
        return $this->hasMany(Tender::class);
    }
}
