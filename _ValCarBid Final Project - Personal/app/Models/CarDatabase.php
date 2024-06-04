<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarDatabase extends Model
{
    use HasFactory;

    protected $table = 'car_database';

    protected $fillable = [
        'make',
        'model',
        'year',
    ];
}
