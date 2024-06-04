<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Listing extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'listings';

    protected $fillable = [
        'car_id',
        'bid_price',
        'buy_price',
        'current_winner_id',
        'created_at',
        'expires_at',
    ];

    public $timestamps = false;

    // Filter options
    public static $sortOptions = [
        'any' => 'Any',
        'bid_price asc' => "Bid Price Asc",
        'bid_price desc' => "Bid Price Desc",
        'buy_price asc' => "Buy Price Asc",
        'buy_price desc' => "Buy Price Desc",
        'expires_at asc' => "Expiration Data Asc",
        'expires_at desc' => "Expiration Data Desc",
    ];

    public static function getListingSortOptions() {
        return static::$sortOptions ?? [];
    }
}
