<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ListingCreateRequest;
use App\Models\Car;
use App\Models\User;
use App\Models\Listing;

// object fit contain - css image

class CarController extends Controller
{
    // Car Create
    public function createCar(array $car, array $images, object $mainImage, int $userID) {
        // Storage the images
        $user = User::find($userID);
        $userName = $user->name;
        $userSurname = $user->surname;

        $userFolder = $userName . " " . $userSurname;
        $carFolder = $car['make'] . " " . $car['model'] . " " . $car['year'];
        $imagePath = "uploads/" . $userFolder . "/" . $carFolder; 

        $imageCount = 1;
        foreach($images as $image) {
            $fileName = "car-image-" . $imageCount++ . '.' . $image->getClientOriginalExtension();
            $image->storeAs($imagePath, $fileName, "public");
        }
        
        $mainImagePath = $imagePath . "/main-image";
        $mainImageName = "car-main-image" . '.' . $image->getClientOriginalExtension();
        $mainImage->storeAs($mainImagePath, $mainImageName, "public");

        // Create Car
        // If the 'cylinders' key in the $car array === '0', then it will become null
        $car['cylinders'] = ($car['cylinders'] === 'null') ? null : $car['cylinders'];
        // If the 'displacement' key in the $car array === '0', then it will become null
        $car['displacement'] = ($car['displacement'] < 0.8) ? null : $car['displacement'];
        // If the 'battery_capacity' key in the $car array === '0', then it will become null
        $car['battery_capacity'] = ($car['battery_capacity'] < 5) ? null : $car['battery_capacity'];
        // If the 'crashes' key in the $car array === 'null', then it will become 0
        $car['crashes'] = ($car['crashes'] === 'null') ? 0 : $car['crashes'];        
        // If the 'crash_description' key in the $car array === '', then it will become null
        $car['crash_description'] = ($car['crash_description'] === '') ? null : $car['crash_description'];
        $car = Car::create($car);

        return($car->id);
    }
}
