<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserMessages;
use App\Models\ExpiredCar;
use App\Models\User;
use App\Models\ExpiredListing;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class UserMessagesController extends Controller
{
    public function createUserMessage(array $userMessage) {
        UserMessages::insert($userMessage);
    }

    public function getWinnerMessages() {
        $winner = Auth::user();

        if ($winner) {
            $winnerId = $winner->id;
            $winnerMessages = UserMessages::where('winner_id', $winnerId)
                             ->whereNull('winner_seen_at')
                             ->get();

            $winnerFinalMessages = [];

            foreach ($winnerMessages as $winnerMessage) {
                $car = ExpiredCar::where('expired_car_id', $winnerMessage->car_id)->first();
                $listing = ExpiredListing::where('expired_listing_id', $winnerMessage->listing_id)->first();
                $seller = User::find($winnerMessage->seller_id);

                $formattedPhoneNumber = "+1" . '(' . substr($seller->phone, 0, 3) . ')' . substr($seller->phone, 3, 3) . '-'  
                . substr($seller->phone, 6, 4);
                $winnerFullName = $winner->name . " " . $winner->surname;
                $carMakeModelYear = $car->make . " " . $car->model . " " . $car->year;

                $carPrice = 0;

                if ($winnerMessage->status === 'bid_won') {
                    $carPrice = $listing->bid_price;
                } else {
                    $carPrice = $listing->buy_price;
                }

                $winnerFinalMessages[] = [
                    'winnerFullName' => $winnerFullName,
                    'carMakeModelYear' => $carMakeModelYear,
                    'carPrice' => $carPrice,
                    'sellerPhoneNumber' => $formattedPhoneNumber,
                    'sellerEmail' => $seller->email,
                    'status' => $winnerMessage->status,
                ];
            }
            return $winnerFinalMessages;
        } else {
            return [];
        }
    }

    public function getSellerMessages() {
        $seller = Auth::user();

        if ($seller) {
            $sellerId = $seller->id;
            $sellerMessages = UserMessages::where('seller_id', $sellerId)
                            ->whereNull('seller_seen_at')
                            ->get();

            $sellerFinalMessages = [];

            foreach ($sellerMessages as $sellerMessage) {
                $car = ExpiredCar::where('expired_car_id', $sellerMessage->car_id)->first();
                $listing = ExpiredListing::where('expired_listing_id', $sellerMessage->listing_id)->first();
                $sellerFullName = $seller->name . " " . $seller->surname;
                $carMakeModelYear = $car->make . " " . $car->model . " " . $car->year;

                if ($sellerMessage->status === 'bid_won' || $sellerMessage->status === 'instant_buy') {
                    $winner = User::find($sellerMessage->winner_id);

                    $formattedPhoneNumber = "+1" . '(' . substr($winner->phone, 0, 3) . ')' . substr($winner->phone, 3, 3) . '-'  
                    . substr($winner->phone, 6, 4);

                    $carPrice = 0;

                    if ($sellerMessage->status === 'bid_won') {
                        $carPrice = $listing->bid_price;
                    } elseif ($sellerMessage->status === 'instant_buy') {
                        $carPrice = $listing->buy_price;
                    }

                    $sellerFinalMessages[] = [
                        'sellerFullName' => $sellerFullName,
                        'carMakeModelYear' => $carMakeModelYear,
                        'carPrice' => $carPrice,
                        'winnerPhoneNumber' => $formattedPhoneNumber,
                        'winnerEmail' => $winner->email,
                        'status' => $sellerMessage->status,
                    ];
                } else {
                    $sellerFinalMessages[] = [
                        'sellerFullName' => $sellerFullName,
                        'carMakeModelYear' => $carMakeModelYear,
                        // 'carPrice' => $listing->buy_price,
                        'status' => $sellerMessage->status,
                    ];
                }
            }
            return $sellerFinalMessages;
        } else {
            return [];
        }
    }

    public function showUserMessages(Request $request) {
        $winnerFinalMessages = $this->getWinnerMessages();
        $sellerFinalMessages = $this->getSellerMessages();
        return view('user-messages', [
            'winnerFinalMessages' => $winnerFinalMessages, 
            'sellerFinalMessages' => $sellerFinalMessages, 
            'iterator' => 1
        ]);
    }

    public function readUserMessages(Request $request) {
        $user = Auth::user();
        $userId = $user->id;
    
        // Update the seller_seen_at column to the current timestamp
        UserMessages::where('seller_id', $userId)
                    ->whereNull('seller_seen_at')
                    ->update(['seller_seen_at' => now()]);
    
        // Update the winner_seen_at column to the current timestamp
        UserMessages::where('winner_id', $userId)
                    ->whereNull('winner_seen_at')
                    ->update(['winner_seen_at' => now()]);
    
        // Fetch messages where both seller and winner have seen the message
        $completedMessages = UserMessages::whereNotNull('winner_seen_at')
                                          ->whereNotNull('seller_seen_at')
                                          ->get();
    
        // Delete completed messages
        $this->deleteCompletedUserMessages($completedMessages);
    
        // Get the updated messages
        $winnerFinalMessages = $this->getWinnerMessages();
        $sellerFinalMessages = $this->getSellerMessages();
    
        return view('user-messages', [
            'winnerFinalMessages' => $winnerFinalMessages, 
            'sellerFinalMessages' => $sellerFinalMessages, 
            'iterator' => 1,
            'warningMessage' => session('warning'), // Pass the warning message to the view
        ]);
    }
    
    public function deleteCompletedUserMessages($completedMessages) {
        // Get car and listing ids
        $carIds = $completedMessages->pluck('car_id')->unique();
        $listingIds = $completedMessages->pluck('listing_id')->unique();
    
        // Soft delete the messages
        UserMessages::whereIn('id', $completedMessages->pluck('id'))->delete();
    
        // Soft delete the cars and listings
        ExpiredCar::whereIn('expired_car_id', $carIds)->delete();
        ExpiredListing::whereIn('expired_listing_id', $listingIds)->delete();
    }
}
