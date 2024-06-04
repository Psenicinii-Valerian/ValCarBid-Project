<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ListingCreateRequest;
use App\Http\Requests\ListingBidOrBuyRequest;
use App\Http\Controllers\File;
use App\Http\Controllers\BidLogController;
use App\Http\Controllers\UserMessagesController;
use App\Models\Car;
use App\Models\User;
use App\Models\State;
use App\Models\City;
use App\Models\Listing;
use App\Models\CarDatabase;
use App\Models\UserMessages;
use App\Models\ExpiredCar;
use App\Models\ExpiredListing;
use App\Models\BidLog;

class ListingController extends Controller
{
    public function getListingFilters() {
        return [
            'typeFilters' => Car::getCarFilters("type"),
            'bodyFilters' => Car::getCarFilters("body"),
            'yearFilters' => Car::getCarFilters("year"),
            'displacementFilters' => Car::getCarFilters("displacement"),
            'batteryCapacityFilters' => Car::getCarFilters("battery-capacity"),
            'transmissionTypeFilters' => Car::getCarFilters("transmission-type"),
            'fuelTypeFilters' => Car::getCarFilters("fuel-type"),
            'crashesFilters' => Car::getCarFilters("crashes"),
        ];
    }

    public function getRemainingTime($expiresAt)
    {
        // Convert the expiration date to a timestamp
        $expiresTimestamp = strtotime($expiresAt);
    
        // Get the current timestamp
        $currentTimestamp = time();
    
        // Calculate the difference in seconds
        $difference = $expiresTimestamp - $currentTimestamp;
    
        if ($difference < 0) {
            // The listing has expired
            return [
                'hours' => 0,
                'minutes' => 0,
                'seconds' => 0,
            ];
        }
    
        // Calculate hours, minutes, and seconds from the difference
        $hours = floor($difference / 3600); // 3600 seconds in an hour
        $difference -= $hours * 3600;
        $minutes = floor($difference / 60);
        $seconds = $difference % 60;
    
        return [
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds,
        ];
    }

    public function getRemainingTimeWithDays($expiresAt)
    {
        // Convert the expiration date to a timestamp
        $expiresTimestamp = strtotime($expiresAt);

        // Get the current timestamp
        $currentTimestamp = time();

        // Calculate the difference in seconds
        $difference = $expiresTimestamp - $currentTimestamp;

        if ($difference < 0) {
            // The listing has expired
            return [
                'days' => 0,
                'hours' => 0,
                'minutes' => 0,
                'seconds' => 0,
            ];
        }

        // Calculate days, hours, minutes, and seconds from the difference
        $days = floor($difference / (3600 * 24)); // 3600 seconds in an hour, 24 hours in a day
        $difference -= $days * 3600 * 24;
        $hours = floor($difference / 3600); // 3600 seconds in an hour
        $difference -= $hours * 3600;
        $minutes = floor($difference / 60);
        $seconds = $difference % 60;

        return [
            'days' => $days,
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds,
        ];
    }
    
    // Show Listings function -> redirects us to index blade
    public function showListings(Request $request) {
        // Get the selected filter values for cars from the request
        $typeFilter = $request->input('type-filter');
        $yearFilter = $request->input('year-filter');
        $bodyFilter = $request->input('body-filter');
        $transmissionTypeFilter = $request->input('transmission-type-filter');
        $fuelTypeFilter = $request->input('fuel-type-filter');
        $crashesFilter = $request->input('crashes-filter');
        if ($request->input('displacement-filter')) {
            $displacementFilter = $request->input('displacement-filter');
        } else {
            $displacementFilter = 0;
        }
        if ($request->input('battery-capacity-filter')) {
            $batteryCapacityFilter = $request->input('battery-capacity-filter');
        } else {
            $batteryCapacityFilter = 0;
        }
        // Get the search query
        $searchQuery = $request->input('search'); 
        // Get the selected sort option from the request
        $sortOption = $request->input('listing-sort');

        // Start with the base query for cars
        $carQuery = Car::query();

        // Apply filters for cars as needed
        if ($typeFilter && $typeFilter !== 'any') {
            $carQuery->where('type', $typeFilter);
        }

        if ($yearFilter && $yearFilter !== 'any') {
            $carQuery->where('year', $yearFilter);
        }

        if ($bodyFilter && $bodyFilter !== 'any') {
            $carQuery->where('body', $bodyFilter);
        }

        if ($transmissionTypeFilter && $transmissionTypeFilter !== 'any') {
            $carQuery->where('transmission_type', $transmissionTypeFilter);
        }

        if ($fuelTypeFilter && $fuelTypeFilter !== 'any') {
            $carQuery->where('fuel_type', $fuelTypeFilter);
        }

        if ($crashesFilter && $crashesFilter !== 'any') {
            $carQuery->where('crashes', $crashesFilter);
        }

        // Apply displacement filter
        if ($displacementFilter && $displacementFilter !== 'any') {
            // Split the selected range (e.g., "1 - 2 litres") into min and max values
            list($minDisplacement, $maxDisplacement) = explode(" - ", $displacementFilter);
            // Apply the filter based on the min and max values
            $carQuery->whereBetween('displacement', [$minDisplacement, $maxDisplacement]);
        }

        // Apply car battery filter
        if ($batteryCapacityFilter && $batteryCapacityFilter !== 'any') {
            // Split the selected range (e.g., "10 - 50 kWh") into min and max values
            list($minBattery, $maxBattery) = explode(" - ", $batteryCapacityFilter);
            // Apply the filter based on the min and max values
            $carQuery->whereBetween('battery_capacity', [$minBattery, $maxBattery]);
        }

        // Apply search filter
        if ($searchQuery) {
            $carQuery->where(function ($query) use ($searchQuery) {
                $query->where('make', 'LIKE', "%$searchQuery%")
                    ->orWhere('model', 'LIKE', "%$searchQuery%");
            });
        }
        // Get the filtered cars
        $filteredCars = $carQuery->get();

        // Get the IDs of the filtered cars
        $filteredCarIds = $filteredCars->pluck('id');
    
        // Start with the base query for listings
        $listingQuery = Listing::query();
    
        // Filter listings based on the car IDs
        $listingQuery->whereIn('car_id', $filteredCarIds)
                     ->where('expires_at', '>', now()); // Check if expires_at is in the future

        // Retrieve expired listings and process them
        $expiredListings = Listing::where('expires_at', '<=', now())->get();

        $this->processExpiredListings($expiredListings);

        // Apply sorting for Listing attributes (bid price and buy price)
        if ($sortOption === 'bid_price asc') {
            $listingQuery->orderBy('bid_price', 'asc');
        } elseif ($sortOption === 'bid_price desc') {
            $listingQuery->orderBy('bid_price', 'desc');
        } elseif ($sortOption === 'buy_price asc') {
            $listingQuery->orderBy('buy_price', 'asc');
        } elseif ($sortOption === 'buy_price desc') {
            $listingQuery->orderBy('buy_price', 'desc');
        } elseif ($sortOption === 'expires_at asc') {
            $listingQuery->orderBy('expires_at', 'asc');
        } elseif ($sortOption === 'expires_at desc') {
            $listingQuery->orderBy('expires_at', 'desc');
        } 
            
        // Paginate the results
        $listings = $listingQuery->paginate(8);

        // Soft delete listings that have expired
        // $expiredListings = Listing::where('expires_at', '<=', now())->get();
        // foreach ($expiredListings as $expiredListing) {
        //     $expiredListing->delete();
        // }
    
        // Fetch related car and user data
        $cars = Car::whereIn('id', $listings->pluck('car_id'))->get();
        $users = User::whereIn('id', $cars->pluck('seller_id'))->get();
    
        // Process the results as before
        $carListings = [];
    
        foreach ($listings as $listing) {
            $matchingCar = $cars->where('id', $listing->car_id)->first();
            $mainImagePath = "";
    
            if ($matchingCar) {
                $matchingUser = $users->where('id', $matchingCar->seller_id)->first();
                $mainImageDirectory = "storage/uploads/{$matchingUser->name} {$matchingUser->surname}/{$matchingCar->make} {$matchingCar->model} {$matchingCar->year}/main-image";

                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

                // Search for an image file in the directory
                foreach ($allowedExtensions as $extension) {
                    $imagePath = "{$mainImageDirectory}/car-main-image.{$extension}";
                    if (file_exists($imagePath)) {
                        $mainImagePath = $imagePath;
                        break; // Stop searching once an image is found
                    }
                    }

                $timeRemaining = $this->getRemainingTime($listing->expires_at);
    
                if ($matchingUser) {
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
        }

        $userMessagesController = new UserMessagesController();
        $winnerMessages = $userMessagesController->getWinnerMessages();
        $sellerMessages = $userMessagesController->getSellerMessages();

        // Pass the filtered and paginated results to the view
        return view("index", [
            'listings' => $listings,
            'carListings' => $carListings,
            'filters' => $this->getListingFilters(),
            'listingSortOptions' => Listing::getListingSortOptions(),
            'winnerMessages' => $winnerMessages,
            'sellerMessages' => $sellerMessages,
        ]);
    }
    

    // showListingCreateForm function -> redirects us to listing-create blade
    public function showListingCreateForm(Request $req) {
        return view("listing-create", [
            'makes' => CarDatabase::distinct()->orderBy('make', 'asc')->pluck('make'),
            'models' => CarDatabase::distinct()->orderBy('model', 'asc')->pluck('model'),
            'years' => CarDatabase::distinct()->pluck('year'),
            'typeOptions' => Car::getCarOptions("type"),
            'bodyOptions' => Car::getCarOptions("body"),
            'iceCylindersOptions' => Car::getCarOptions("ice-cylinders"),
            'iceTransmissionTypeOptions' => Car::getCarOptions("ice-transmission"),
            'driveTypeOptions' => Car::getCarOptions("drive-type"),
            'iceFuelTypeOptions' => Car::getCarOptions("ice-fuel"),
            'doorCountOptions' => Car::getCarOptions("door-count"),
            'capacityOptions' => Car::getCarOptions("capacity"),
            'crashesOptions' => Car::getCarOptions("crashes"),
            'daysToSellOptions' => Car::getCarOptions("days-to-sell"),
        ]);
    }   

    public function createListing(ListingCreateRequest $req) {
        // Storage userID int value
        $userID = Auth::user()->id;

        // Storage Car Array
        $car = [
            'make' => ucwords(strtolower($req['make'])),
            'model' => ucwords(strtolower($req['model'])),
            'year' => $req['year'],
            'type' => $req['type'],
            'body' => $req['body'],
            'mileage' => $req['mileage'],
            'vin' => $req['vin'],
            'cylinders' => $req['cylinders'],
            'engine_power' => $req['engine-power'],
            'displacement' => $req['displacement'], 
            'battery_capacity' => $req['battery-capacity'],
            'transmission_type' => $req['transmission-type'],
            'drive_type' => $req['drive-type'],
            'fuel_type' => $req['fuel-type'],
            'door_count' => $req['door-count'],
            'capacity' => $req['capacity'],
            'crashes' => $req['crashes'],
            'crash_description' => ucfirst(strtolower($req['crash-description'])),
            'seller_id' => $userID,
        ];
        
        // Storage Main Image
        $mainImage = $req->file("main-image");
        // Storage Image Array
        $images = $req->file("images");
        
        // Send the storages values in CarController for further usage
        $carController = new CarController();
        $carID = $carController->createCar($car, $images, $mainImage, $userID);

        // Create listing
        $currentDateTime = date('Y-m-d H:i:s'); 
        // Add the value from $req['days-to-sell'] to the current date and time
        $expireDateTime = date('Y-m-d H:i:s', strtotime($currentDateTime . "+{$req['days-to-sell']} days")); 
        // Prepare listing array
        Listing::Create([
            'car_id' => $carID,
            'bid_price' => $req['bid-price'],
            'buy_price' => $req['buy-price'],
            'current_winner_id' => null,
            'created_at' => $currentDateTime,
            'expires_at' => $expireDateTime,
        ]);

        return redirect('/')->with('success_msg', 'Listing created!');
    }

    public function listingDetailedInfo(int $id) {
        // Retrieve current user id
        $userID = null;
        if (isset(Auth::user()->id)) {
            $userID = Auth::user()->id;
        } 

        // Retrieve the car by its id
        $car = Car::find($id);

        // Retrieve the listing where car_id matches $id
        $listing = Listing::where('car_id', $id)->first();

        // Check if the listing exists and is not expired
        if (!$listing || $listing->expires_at <= now()) {
            // Listing doesn't exist or is expired
            return redirect('index');
        }

        $seller = User::find($car->seller_id);

        $currentBidWinner = null;
        if (isset($listing->current_winner_id)) {
            $currentBidWinner = User::find($listing->current_winner_id);
            $currentBidWinner = $currentBidWinner->name . " " . $currentBidWinner->surname;
        }

        $sellerState = State::find($seller->state)->name;
        $sellerCity = City::find($seller->city)->name;

        $sellername = $seller->name . " " . $seller->surname;
        
        $mainImagePath = "";
        $mainImageDirectory = "storage/uploads/{$sellername}/{$car->make} {$car->model} {$car->year}/main-image";
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        // Search for an image file in the directory
        foreach ($allowedExtensions as $extension) {
            $imagePath = "{$mainImageDirectory}/car-main-image.{$extension}";
            if (file_exists($imagePath)) {
                $mainImagePath = $imagePath;
                break; // Stop searching once an image is found
            }
        }

        $images[] = $mainImagePath;

        $otherImagesDirectory = "storage/uploads/{$sellername}/{$car->make} {$car->model} {$car->year}";
        $imagesIterator = 1;
        // Search for other images and add their paths to the $images array
        while (true) {
            $otherImagePath = "{$otherImagesDirectory}/car-image-{$imagesIterator}.{$allowedExtensions[0]}";
            if (file_exists($otherImagePath)) {
                $images[] = $otherImagePath; // Add the path to the $images array
                $imagesIterator++;
            } else {
                break; // Break the loop if no more images are found
            }
        }

        $timeRemaining = $this->getRemainingTimeWithDays($listing->expires_at);

        return view("/listing-detailed-info", [
            'userID' => $userID,
            'sellerState' => $sellerState,
            'sellerCity' => $sellerCity ,
            'listing' => $listing,
            'car' => $car,
            'images' => $images,
            'currentBidWinner' => $currentBidWinner,
            'timeRemaining' => $timeRemaining,
            'seller' => $seller,
        ]);
    }

    public function listingBidOrBuy(listingBidOrBuyRequest $req) {
        $listing = Listing::find($req['listing-id']);
        $car = Car::find($req['car-id']);
        $seller = User::find($req['seller-id']);
        $user = User::find($req['user-id']);
        
        if ($req['new-bid'] !== null) {
            // Update $listing->bid_price with $req['new-bid']
            $listing->bid_price = $req['new-bid'];
            $listing->current_winner_id = $user->id;

            // Set the expire time to +1 minute
            $expiresAtTimestamp = strtotime($listing->expires_at);
            $currentTimestamp = time();
            $oneMinuteFromNow = $currentTimestamp + 60;

            if ($expiresAtTimestamp <= $oneMinuteFromNow) {
                // The listing will expire in less than 1 minute, so add 1 more minute to the expiration time
                $listing->expires_at = date('Y-m-d H:i:s', $oneMinuteFromNow);
            }

            $listing->save(); // Save the updated listing

            // Set the Bid Log (soft delete if buying)
            $bidLogData = [
            'car_id' => $car->id,
            'bidder_id' => $user->id,
            'listing_id' => $listing->id,
            'bid_price' => $req['new-bid'],
            'created_at' => now(),
            ];

            $bidLogController = new BidLogController();
            $bidLogController->createBidLog($bidLogData);
            return redirect()->route('listing.show.detailed', ['id' => $car->id])->with('success_msg', 'Bid successfully placed!');
        } else {
            $sellername = $seller->name . " " . $seller->surname;

            $this->deleteCarImages($sellername, $car);

            $userMessagesController = new UserMessagesController();

            $listing->current_winner_id = $user->id;

            $userMessage = [
                'car_id' => $car->id,
                'listing_id' => $listing->id,
                'winner_id' => $listing->current_winner_id,
                'seller_id' => $car->seller_id,
                'status' => "instant_buy",
            ];

            $userMessagesController->createUserMessage($userMessage);
            $listing->expires_at = now();
            $listing->save(); // Save the updated expires_at value

            // Create an entry in the ExpiredCars model
            ExpiredCar::create([
                'expired_car_id' => $car->id,
                'make' => $car->make,
                'model' => $car->model,
                'year' => $car->year,
                'type' => $car->type,
                'body' => $car->body,
                'mileage' => $car->mileage,
                'vin' => $car->vin,
                'cylinders' => $car->cylinders,
                'engine_power' => $car->engine_power,
                'displacement' => $car->displacement,
                'battery_capacity' => $car->battery_capacity,
                'transmission_type' => $car->transmission_type,
                'drive_type' => $car->drive_type,
                'fuel_type' => $car->fuel_type,
                'door_count' => $car->door_count,
                'capacity' => $car->capacity,
                'crashes' => $car->crashes,
                'crash_description' => $car->crash_description,
                'seller_id' => $car->seller_id,
            ]);

            // Create an entry in the ExpiredListings model
            ExpiredListing::create([
                'expired_listing_id' => $listing->id,
                'expired_car_id' => $car->id,
                'bid_price' => $listing->bid_price,
                'buy_price' => $listing->buy_price,
                'current_winner_id' => $listing->current_winner_id,
                'created_at' => $listing->created_at,
                'expires_at' => $listing->expires_at,
            ]);

            // Soft delete the bid log on instant buy
            BidLog::where('listing_id', $listing->id)->delete();
            // Finally, delete the Listing and Car models
            $listing->delete();
            $car->delete();
        }
        return redirect()->route('listings.show')->with('success_msg', 'Car successfully bought!');
    }

    public function showUserListings(Request $req) {
        $user = Auth::user();
        $userId = $user->id;

        // Retrieve cars with the extracted user_id
        $cars = Car::where('seller_id', $userId)->get();
        $carIds = $cars->pluck('id')->toArray();

        $listings = Listing::whereIn('car_id', $carIds)
                            ->where('expires_at', '>', now())
                            ->get();

        // Retrieve expired listings and process them
        $expiredListings = Listing::where('expires_at', '<=', now())
                            ->get();

        $this->processExpiredListings($expiredListings);

        // Process the results as before - for index
        $carListings = [];
            
        foreach ($listings as $listing) {
            $matchingCar = $cars->where('id', $listing->car_id)->first();
            $mainImagePath = "";

            if ($matchingCar) {
                $mainImageDirectory = "storage/uploads/{$user->name} {$user->surname}/{$matchingCar->make} {$matchingCar->model} {$matchingCar->year}/main-image";

                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

                // Search for an image file in the directory
                foreach ($allowedExtensions as $extension) {
                    $imagePath = "{$mainImageDirectory}/car-main-image.{$extension}";
                    if (file_exists($imagePath)) {
                        $mainImagePath = $imagePath;
                        break; // Stop searching once an image is found
                    }
                }

                $timeRemaining = $this->getRemainingTime($listing->expires_at);

                if ($user) {
                    $combined = [
                        'listing' => $listing,
                        'car' => $matchingCar,
                        'user' => $user,
                        'imagePath' => $mainImagePath,
                        'remainingHours' => $timeRemaining['hours'],
                        'remainingMinutes' => $timeRemaining['minutes'],
                        'remainingSeconds' => $timeRemaining['seconds'],
                    ];
                    $carListings[] = $combined;
                }
            }
        }

        // Convert $carListings array to a collection
        $carListingsCollection = collect($carListings);

        // Define the number of items to display per page (e.g., 4 items per page)
        $perPage = 4; // You can adjust this to your desired value

        // Create a LengthAwarePaginator instance without appending query parameters
        $paginatedCarListings = new \Illuminate\Pagination\LengthAwarePaginator(
            $carListingsCollection->forPage(request('page'), $perPage),
            $carListingsCollection->count(),
            $perPage,
            null,
            ['path' => route('user.listings')] // Use the correct route name or URL
        );

        // Pass the filtered and paginated results to the view
        return view("user-listings", [
            'carListings' => $paginatedCarListings,
        ]);
    }

    public function processExpiredListings(Collection $expiredListings) {
        $userMessagesController = new UserMessagesController();

        // Filter the $expiredListings collection to exclude those that are already saved
        $unprocessedListings = $expiredListings->filter(function ($expiredListing) {
            // Check if there is already a user message for this expired listing
            return !UserMessages::where('listing_id', $expiredListing->id)->exists();
        });

        foreach ($unprocessedListings as $unprocessedListing) {
            $car = Car::find($unprocessedListing->car_id);
            $seller = User::find($car->seller_id);
            $sellername = $seller->name . " " . $seller->surname;

           $this->deleteCarImages($sellername, $car);

            if (isset($unprocessedListing->current_winner_id)) {
                $userMessage = [
                    'car_id' => $car->id,
                    'listing_id' => $unprocessedListing->id,
                    'winner_id' => $unprocessedListing->current_winner_id,
                    'seller_id' => $car->seller_id,
                    'status' => "bid_won",
                ];
            } else {
                $userMessage = [
                    'car_id' => $car->id,
                    'listing_id' => $unprocessedListing->id,
                    'winner_id' => null,
                    'seller_id' => $car->seller_id,
                    'status' => "auction_expired",
                ];
            }

            $userMessagesController->createUserMessage($userMessage);

            // Create an entry in the ExpiredCars model
            ExpiredCar::create([
                'expired_car_id' => $car->id,
                'make' => $car->make,
                'model' => $car->model,
                'year' => $car->year,
                'type' => $car->type,
                'body' => $car->body,
                'mileage' => $car->mileage,
                'vin' => $car->vin,
                'cylinders' => $car->cylinders,
                'engine_power' => $car->engine_power,
                'displacement' => $car->displacement,
                'battery_capacity' => $car->battery_capacity,
                'transmission_type' => $car->transmission_type,
                'drive_type' => $car->drive_type,
                'fuel_type' => $car->fuel_type,
                'door_count' => $car->door_count,
                'capacity' => $car->capacity,
                'crashes' => $car->crashes,
                'crash_description' => $car->crash_description,
                'seller_id' => $car->seller_id,
            ]);

            // Create an entry in the ExpiredListings model
            ExpiredListing::create([
                'expired_listing_id' => $unprocessedListing->id,
                'expired_car_id' => $car->id,
                'bid_price' => $unprocessedListing->bid_price,
                'buy_price' => $unprocessedListing->buy_price,
                'current_winner_id' => $unprocessedListing->current_winner_id,
                'created_at' => $unprocessedListing->created_at,
                'expires_at' => $unprocessedListing->expires_at,
            ]);

            // Finally, delete the Listing and Car models
            $unprocessedListing->delete();
            $car->delete();
        }
    }

    public function deleteCarImages($sellername, $car) {
        $mainImageDirectory = "storage/uploads/$sellername/{$car->make} {$car->model} {$car->year}/main-image";
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    
        // Search for an image file in the directory
        foreach ($allowedExtensions as $extension) {
            $imagePath = "$mainImageDirectory/car-main-image.$extension";
            if (file_exists($imagePath)) {
                // Delete the image file
                unlink($imagePath);
                break; // Stop searching once an image is found and deleted
            }
        }
    
        // Check if the main car's image directory is empty
        if (is_dir($mainImageDirectory) && count(scandir($mainImageDirectory)) == 2) {
            rmdir($mainImageDirectory); // Delete the directory if it's empty
        }
        
        // Delete car's other images
        $imagesToDelete = [];
    
        $otherImagesDirectory = "storage/uploads/$sellername/{$car->make} {$car->model} {$car->year}";
        $imagesIterator = 1;
    
        // Search for other images and add their paths to the $imagesToDelete array
        while (true) {
            $otherImagePath = "$otherImagesDirectory/car-image-$imagesIterator.$allowedExtensions[0]";
            if (file_exists($otherImagePath)) {
                $imagesToDelete[] = $otherImagePath; // Add the path to the $imagesToDelete array
                $imagesIterator++;
            } else {
                break; // Break the loop if no more images are found
            }
        }
    
        // Delete all images in the $imagesToDelete array
        foreach ($imagesToDelete as $imagePath) {
            unlink($imagePath);
        }
    
        // Check if the other car's images directory is empty
        if (is_dir($otherImagesDirectory) && count(scandir($otherImagesDirectory)) == 2) {
            rmdir($otherImagesDirectory); // Delete the directory if it's empty
        }
    }
}




