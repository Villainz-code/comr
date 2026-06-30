<?php

namespace App\Http\Controllers;

use App\Models\Regency;
use App\Models\District;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function getRegencies($province_id)
    {
        $regencies = Regency::where('province_id', $province_id)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($regencies);
    }

    public function getDistricts($regency_id)
    {
        $districts = District::where('regency_id', $regency_id)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($districts);
    }
}
