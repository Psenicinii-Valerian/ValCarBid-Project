<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class BidLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bid_log';

    protected $fillable = [
        'car_id',
        'bidder_id',
        'listing_id',
    ];
}
