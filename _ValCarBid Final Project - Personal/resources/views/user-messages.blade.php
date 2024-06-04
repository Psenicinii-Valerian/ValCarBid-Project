@extends('layouts.layout')

@section('doc_scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
@endsection

@section('doc_title', 'User Messages')
@section('doc_body')
    <div class="messages">
        <h1 class="user-msg-header">User Messages</h1>

        @if (session('warning'))
            <div class="alert alert-warning">
                {{ session('warning') }}
            </div>
        @endif
        @if (count($winnerFinalMessages) > 0 || count($sellerFinalMessages) > 0)
            <form action="user-messages" method="POST">
                @csrf
                <div class="user-messages">
                    @foreach ($winnerFinalMessages as $message)
                        <div class="user-message">
                            @if ($message['status'] === 'bid_won')
                                <p>{{ $iterator++ }}. Mr./Mr.s </p>
                                <p class="winnerName deal-detail">{{ $message['winnerFullName'] }}, </p>
                                <p> congratulations! You have won the auction for the car </p>
                                <p class="winnerCarName deal-detail"> {{ $message['carMakeModelYear'] }} </p>
                                <p> with a bidding of </p>
                                <p class="winnerCarPrice deal-detail"> ${{ $message['carPrice'] }}.</p>
                                <p>
                                    You can now contact the seller at the phone number:
                                    <a href="tel:"
                                        class="sellerPhoneNumber deal-detail">{{ $message['sellerPhoneNumber'] }}</a>,
                                </p>
                                <p>
                                    or via email at:
                                    <a href="mailto:" class="sellerMail deal-detail">{{ $message['sellerEmail'] }}</a>.
                                </p>
                                <p>Enjoy your new car!</p>
                            @else
                                <p>{{ $iterator++ }}. Mr./Mr.s </p>
                                <p class="winnerName deal-detail">{{ $message['winnerFullName'] }}, </p>
                                <p> congratulations! You have successfully purchased the car </p>
                                <p class="winnerCarName deal-detail"> {{ $message['carMakeModelYear'] }} </p>
                                <p> for a total price of </p>
                                <p class="winnerCarPrice deal-detail"> ${{ $message['carPrice'] }}.</p>
                                <p>
                                    You can now contact the seller at the phone number:
                                    <a href="tel:"
                                        class="sellerPhoneNumber deal-detail">{{ $message['sellerPhoneNumber'] }}</a>,
                                </p>
                                <p>
                                    or via email at:
                                    <a href="mailto:" class="sellerMail deal-detail">{{ $message['sellerEmail'] }}</a>.
                                </p>
                                <p>Enjoy your new car!</p>
                            @endif
                        </div>
                    @endforeach

                    @foreach ($sellerFinalMessages as $message)
                        <div class="user-message">
                            @if ($message['status'] === 'auction_expired')
                                <p>{{ $iterator++ }}. Mr./Mr.s </p>
                                <p class="sellerName deal-detail">{{ $message['sellerFullName'] }}, </p>
                                <p> unfortunately, your auction for the car </p>
                                <p class="sellerCarName deal-detail"> {{ $message['carMakeModelYear'] }} </p>
                                <p> has expired with no bids or purchases. </p>
                                <p> You can consider re-listing it with a different price. </p>
                            @elseif ($message['status'] === 'bid_won')
                                <p>{{ $iterator++ }}. Mr./Mr.s </p>
                                <p class="sellerName deal-detail">{{ $message['sellerFullName'] }}, </p>
                                <p> congratulations! Your auction for the car </p>
                                <p class="sellerCarName deal-detail"> {{ $message['carMakeModelYear'] }} </p>
                                <p> has been won at the bidding with a price of </p>
                                <p class="sellerCarPrice deal-detail"> ${{ $message['carPrice'] }}.</p>
                                <p>
                                    You can now contact the buyer at the phone number:
                                    <a href="tel:"
                                        class="winnerPhoneNumber deal-detail">{{ $message['winnerPhoneNumber'] }}</a>,
                                </p>
                                <p>
                                    or via email at:
                                    <a href="mailto:" class="winnerMail deal-detail">{{ $message['winnerEmail'] }}</a>.
                                </p>
                                <p>Wishing you success with future sales!</p>
                            @else
                                <p>{{ $iterator++ }}. Mr./Mr.s </p>
                                <p class="sellerName deal-detail">{{ $message['sellerFullName'] }}, </p>
                                <p> congratulations! Your auction for the car </p>
                                <p class="sellerCarName deal-detail"> {{ $message['carMakeModelYear'] }} </p>
                                <p> has been purchased with a price of </p>
                                <p class="sellerCarPrice deal-detail"> ${{ $message['carPrice'] }}.</p>
                                <p>
                                    You can now contact the buyer at the phone number:
                                    <a href="tel:"
                                        class="winnerPhoneNumber deal-detail">{{ $message['winnerPhoneNumber'] }}</a>,
                                </p>
                                <p>
                                    or via email at:
                                    <a href="mailto:" class="winnerMail deal-detail">{{ $message['winnerEmail'] }}</a>.
                                </p>
                                <p>Wishing you success with future sales!</p>
                            @endif
                        </div>
                    @endforeach
                </div>
                <div class="mark-as-read">
                    <button type="submit" class="mark-as-read-btn">Mark as read</button>
                </div>
            </form>
        @elseif (!session('warning'))
            <div class="no-messages">
                <p>No user messages available!</p>
            </div>
        @endif
    </div>
@endsection
