<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        // Return districts with subdistricts for dropdowns
        return response()->json(\App\Models\District::with('subdistricts')->get());
    }
}
