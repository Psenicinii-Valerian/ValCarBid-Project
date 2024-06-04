<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Car extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cars';

    protected $fillable = [
        'make',
        'model',
        'year',
        'body',
        'type',
        'mileage',
        'vin',
        'cylinders',
        'engine_power',
        'displacement',
        'battery_capacity',
        'transmission_type',
        'drive_type',
        'fuel_type',
        'door_count',
        'capacity',
        'crashes',
        'crash_description',
        'seller_id',
    ];

    public $timestamps = false;

    // Car Options
    protected static $options = [
        'type' => [
            'ice' => 'Internal Combustion Engine (ICE)',
            'ev' => 'Electric Vehicle (EV)',
        ],
        'body' => [
            'sedan' => 'Sedan',
            'suv' => 'SUV',
            'hatchback' => 'Hatchback',
            'truck' => 'Truck',
            'convertible' => 'Convertible',
            'coupe' => 'Coupe',
            'van' => 'Van',
            'wagon' => 'Wagon',
        ],
        'ice-cylinders' => [
            '3' => '3 Cylinders',
            '4' => '4 Cylinders',
            '6' => '6 Cylinders',
            '8' => '8 Cylinders',
            '12' => '12 Cylinders',
        ],
        'ev-cylinders' => [
            'null' => 'Not Any',
        ],
        'ice-transmission-type' => [
            'manual' => 'Manual',
            'automatic' => 'Automatic',
            'cvt' => 'CVT',
        ],
        'ev-transmission-type' => [
            'automatic' => 'Automatic',
        ],
        'drive-type' => [
            'awd' => 'All-Wheel Drive (AWD)',
            'fwd' => 'Front-Wheel Drive (FWD)',
            'rwd' => 'Rear-Wheel Drive (RWD)',
            '4wd' => 'Four-Wheel Drive (4WD)',
        ],
        'ice-fuel-type' => [
            'gasoline' => 'Gasoline',
            'diesel' => 'Diesel',
            'ethanol' => 'Ethanol',
            'hybrid' => 'Hybrid',
        ],
        'ev-fuel-type' => [
            'electricity' => 'Electricity',
        ],
        'door-count' => [
            '3' => 'Three doors',
            '5' => 'Five doors',
        ],
        'capacity' => [
            '2' => 'Two people',
            '4' => 'Four people',
            '5' => 'Five people',
            '7' => 'Seven people',
        ],
        'crashes' => [
            '1' => 'Yes',
            'null' => 'No',
        ],
        'days-to-sell' => [
            '4' => '4 Days',
            '6' => '6 Days',
            '10' => '10 Days',
            '15' => '15 Days',
            '30' => '30 Days',
        ],  
    ];

    public static function getCarOptions($option)
    {
        return static::$options[$option] ?? [];
    }

    // Filter options
    public static $filters = [
        'type' => [
            'any' => 'Any',
            'ice' => 'ICE',
            'ev' => 'EV',
        ],
        'year' => [
            'any' => 'Any',
            '2010' => '2010',
            '2011' => '2011',
            '2012' => '2012',
            '2013' => '2013',
            '2014' => '2014',
            '2015' => '2015',
            '2016' => '2016',
            '2017' => '2017',
            '2018' => '2018',
            '2019' => '2019',
            '2020' => '2020',
            '2021' => '2021',
            '2022' => '2022',
            '2023' => '2023',
        ],
        'body' => [
            'any' => 'Any',
            'sedan' => 'Sedan',
            'suv' => 'SUV',
            'hatchback' => 'Hatchback',
            'truck' => 'Truck',
            'convertible' => 'Convertible',
            'coupe' => 'Coupe',
            'van' => 'Van',
            'wagon' => 'Wagon',
        ],
        'transmission-type' => [
            'any' => 'Any',
            'manual transmission' => 'Manual',
            'automatic transmission' => 'Automatic',
            'cvt' => 'CVT',
        ],
        'displacement' => [
            'any' => 'Any',
            '0.8 - 0.9' => '0.8 - 1 litres',
            '1 - 1.9' => '1-2 litres',
            '2 - 2.9' => '2-3 litres',
            '3 - 3.9' => '3-4 litres',
            '4 - 4.9' => '4-5 litres',
            '5 - 5.9' => '5-6 litres',
            '6 - 6.9' => '6-7 litres',
            '7 - 7.9' => '7-8 litres',
            '8 - 8.9' => '8-9 litres',
            '9 - 9.9' => '9-10 litres',
            '10 - 10.9' => '10-11 litres',
            '11 - 12' => '11-12 litres',
        ],
        'battery-capacity' => [
            'any' => 'Any',
            '10 - 49.9' => '10 - 50 kWh',
            '50 - 99.9'=> '50 - 100 kWh',
            '100 - 149.9' => '100 - 150 kWh',
            '150 - 200' => '150 - 200 kWh'
        ],
        'fuel-type' => [
            'any' => 'Any',
            'gasoline' => 'Gasoline',
            'diesel' => 'Diesel',
            'ethanol' => 'Ethanol',
            'hybrid' => 'Hybrid',
            'electricity' => 'Electricity',
        ],
        'crashes' => [
            'any' => 'Any',
            '1' => 'Yes',
            'null' => 'No',
        ],
    ];

    public static function getCarFilters($filter) {
        return static::$filters[$filter] ?? [];
    }
}
