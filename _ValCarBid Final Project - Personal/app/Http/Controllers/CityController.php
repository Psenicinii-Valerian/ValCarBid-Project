<?php

namespace App\Http\Controllers;

use App\Models\City;

class CityController extends Controller
{
    public function getCitiesStateBased()
    {
        $stateId = request()->input('state_id');
        
        $cities = City::whereHas('state', function($query) use ($stateId) {
                $query->whereId($stateId);
            })
            ->get(['id', 'name']);
    
        $uniqueCities = $cities->unique('name');
    
        $result = $uniqueCities->pluck('name', 'id');
        
        return response()->json($result);
    }

    // Previous code
    // public function getCitiesStateBased()
    // {
    //     $stateId = request()->input('state_id');
        
    //     $cities = City::whereHas('state', function($query) use ($stateId) {
    //             $query->whereId($stateId);
    //         })
    //         ->pluck('name', 'id');
    
    //     return response()->json($cities);
    // }
}
