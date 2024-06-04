<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BidLog;
use App\Models\Listing;
use App\Models\Car;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class BidLogController extends Controller
{
    public function createBidLog(array $bidLogData) {
        BidLog::insert($bidLogData);
    }

    public function ShowUserBids(Request $req) {
        $userId = Auth::user()->id;
    
        // Fetch bids by user and their unique listing_ids
        $bids = BidLog::where('bidder_id', $userId)->get();
        $uniqueBids = $bids->unique('listing_id');
        $listingIds = $uniqueBids->pluck('listing_id')->toArray();
    
        // Retrieve active and expired listings
        $activeListings = Listing::whereIn('id', $listingIds)->where('expires_at', '>', now())->get();
        $expiredListings = Listing::where('expires_at', '<=', now())->get();
    
        // Process expired listings
        $listingController = new ListingController();
        $listingController->processExpiredListings($expiredListings);
    
        // Delete bids associated with expired listings
        foreach ($expiredListings as $expiredListing) {
            BidLog::where('listing_id', $expiredListing->id)->delete();
        }
    
        // Retrieve associated cars and users for active listings
        $carIds = $activeListings->pluck('car_id')->toArray();
        $cars = Car::whereIn('id', $carIds)->get();
        $userIds = $cars->pluck('seller_id')->toArray();
        $users = User::whereIn('id', $userIds)->get();
    
        // Prepare car listings for the view
        $carListings = [];
        foreach ($activeListings as $listing) {
            $matchingCar = $cars->where('id', $listing->car_id)->first();
            $mainImagePath = $this->getMainImagePath($matchingCar, $users);
    
            $timeRemaining = $listingController->getRemainingTime($listing->expires_at);
    
            $matchingUser = $users->where('id', $matchingCar->seller_id)->first();
            if ($matchingCar && $matchingUser) {
                $combined = [
                    'listing' => $listing,
                    'car' => $matchingCar,
                    'user' => $matchingUser,
                    'imagePath' => $mainImagePath,
                    'remainingHours' => $timeRemaining['hours'],
                    'remainingMinutes' => $timeRemaining['minutes'],
                    'remainingSeconds' => $timeRemaining['seconds'],
                ];
                $carListings[] = $combined;
            }
        }
    
        // Paginate the car listings
        $carListingsCollection = collect($carListings);
        $perPage = 4;
        $paginatedCarListings = new \Illuminate\Pagination\LengthAwarePaginator(
            $carListingsCollection->forPage(request('page', 1), $perPage),
            $carListingsCollection->count(),
            $perPage,
            request('page', 1),
            ['path' => route('user.bids')]
        );
    
        // Pass the filtered and paginated results to the view
        return view("user-bids", [
            'carListings' => $paginatedCarListings,
        ]);
    }
    
    // Helper function to get main image path
    private function getMainImagePath($car, $users) {
        if (!$car) {
            return '';
        }
    
        $matchingUser = $users->where('id', $car->seller_id)->first();
        if (!$matchingUser) {
            return '';
        }
    
        $mainImageDirectory = "storage/uploads/{$matchingUser->name} {$matchingUser->surname}/{$car->make} {$car->model} {$car->year}/main-image";
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    
        foreach ($allowedExtensions as $extension) {
            $imagePath = "{$mainImageDirectory}/car-main-image.{$extension}";
            if (file_exists($imagePath)) {
                return $imagePath;
            }
        }
    
        return '';
    }
    

    public function showCarBidLog(Request $req, int $id) {
        $bids = BidLog::where('car_id', $id)->get();

        $carBidLog = [];

        foreach ($bids as $bid) {
            $car = Car::find($bid->car_id);
            $listing = Listing::find($bid->listing_id);
            $bidder = User::find($bid->bidder_id);
            
            $carBidLog[] = [
                'car' => $car,
                'listing' => $listing,
                'bidder' => $bidder,
                'bid' => $bid,
            ];
        }

        $iterator = 1;

        return view("car-bid-log", ['carBidLog' => $carBidLog, 'iterator' => $iterator]);
    }
}
