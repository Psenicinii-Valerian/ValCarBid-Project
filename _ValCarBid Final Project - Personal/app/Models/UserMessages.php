<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class UserMessages extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'user_messages';

    protected $fillable = [
        'car_id',
        'listing_id',
        'winner_id',
        'seller_id',
        'status'
    ];
}
