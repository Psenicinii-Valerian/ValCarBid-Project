<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class ExpiredCar extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'expired_cars';

    protected $fillable = [
        'expired_car_id',
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
}
