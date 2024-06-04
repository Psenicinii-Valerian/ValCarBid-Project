<?php

namespace App\Http\Controllers;

use App\Models\CarDatabase;
use Illuminate\Http\Request;

class CarDatabaseController extends Controller
{
    public function filterCars(Request $request)
    {
        $make = $request->input('make');
        $model = $request->input('model');
        $year = $request->input('year');
    
        // Query the database based on selected filters
        $query = CarDatabase::query();
    
        // Check if "Make" is selected and add to the query
        if ($make) {
            $query->where('make', $make);
        }
    
        // Check if "Model" is selected and add to the query
        if ($model) {
            $query->where('model', $model);
        }
    
        // Check if "Year" is selected and add to the query
        if ($year) {
            $query->where('year', $year);
        }
    
        $cars = $query->get();
    
        // Fetch distinct makes, models, and years from the result set
        $makes = $cars->pluck('make')->unique()->values()->all();
        sort($makes);
        
        $models = $cars->pluck('model')->unique()->values()->all();
        sort($models);
        
        $years = $cars->pluck('year')->unique()->values()->all();
        sort($years);
    
        // Return the data in the expected format for the dropdowns
        return response()->json([
            'makes' => $makes,
            'models' => $models,
            'years' => $years,
        ]);
    }
}
