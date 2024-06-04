<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CarDatabaseController;
use App\Http\Controllers\BidLogController;
use App\Http\Controllers\UserMessagesController;
use App\Actions\Fortify\UpdateUserProfileInformation;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [ListingController::class, "showListings"])->name('listings.show');

Route::middleware([
    'auth:sanctum',     
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});



Route::get('/listing-create', [ListingController::class, "showListingCreateForm"])->middleware("verified");

Route::post('/listing-create', [ListingController::class, "createListing"])->name('listing.create')->middleware("verified");

Route::get('/get-cities', [CityController::class, 'getCitiesStateBased'])->name('get-cities');

Route::post('/user/profile', [UpdateUserProfileInformation::class, 'update']);

Route::get('/filter-cars', [CarDatabaseController::class, 'filterCars'])->name('filter-cars');

Route::get("/listing/{id}", [ListingController::class, "listingDetailedInfo"])->name('listing.show.detailed');

Route::post("/listing/{id}", [ListingController::class, "listingBidOrBuy"])->middleware("verified");

Route::get("/user-bids", [BidLogController::class, "ShowUserBids"])->name('user.bids')->middleware("verified");

Route::get("/user-listings", [ListingController::class, "ShowUserListings"])->name('user.listings')->middleware("verified");

Route::get("/listing/car-bid-log/{id}", [BidLogController::class, "showCarBidLog"])->name('car.bidlog');

Route::get("/user-messages", [UserMessagesController::class, "showUserMessages"])->name('user.showMessages')->middleware("verified");

Route::post("/user-messages", [UserMessagesController::class, "readUserMessages"])->name('user.readMessages')->middleware("verified");