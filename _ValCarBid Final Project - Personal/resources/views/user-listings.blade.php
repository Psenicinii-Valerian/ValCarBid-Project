@extends('layouts.layout')

@section('doc_scripts')
    <script src='{{ asset('js/index-expire-time-trigger.js') }}' defer></script>
    <script src='{{ asset('js/index-listings-styling.js') }}' defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
@endsection

@section('doc_title', 'User Listings')
@section('doc_body')
    @if (count($carListings) > 0)
    <div class="listings user-listings">
        @foreach ($carListings as $carListing)
            <a href="{{ '/listing/' . $carListing['car']->id }}" class="listing">
                <img src="{{ asset($carListing['imagePath']) }}" alt="Main Car Image">
                <div class="car-info">
                    <p class="car-make-model">
                        {{ strtoupper($carListing['car']->make . ' ' . $carListing['car']->model) }}</p>
                    <p class="car-year">{{ $carListing['car']->year }}</p>
                    <div class="prices-timer">
                        <div class="prices">
                            <p>Bid: ${{ $carListing['listing']->bid_price }}</p>
                            <p class="car-buy">Buy: ${{ $carListing['listing']->buy_price }}</p>
                        </div>
                        <p id="expires-in" class="timer">
                            {{ sprintf('%02d', $carListing['remainingHours']) }} : 
                            {{ sprintf('%02d', $carListing['remainingMinutes']) }} : 
                            {{ sprintf('%02d', $carListing['remainingSeconds']) }}
                        </p>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
    <p id="paginator">{{ $carListings->links() }}</p>
    @else
    <div class="no-listings">
        <!-- HTML for when there are no car listings -->
        <p>No car listings created for this user yet!</p>
    </div>
    @endif
@endsection