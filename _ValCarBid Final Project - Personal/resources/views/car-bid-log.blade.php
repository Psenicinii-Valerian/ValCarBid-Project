@extends('layouts.layout')

@section('doc_scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
@endsection

@section('doc_title', 'Car Bid Log')
@section('doc_body')
    @if (count($carBidLog) > 0)
        <div class="car-bid-log">
            @foreach ($carBidLog as $carBid)
                <div class="one-car-bid-log">
                    <p> {{ $iterator++ }}. </p>
                    <p class="car-bidder"> {{ $carBid['bidder']->name }} {{ $carBid['bidder']->surname }} </p>
                    <p> bidded </p>
                    <p class="bid-price"> ${{ $carBid['bid']->bid_price }} </p>
                    <p> on </p>
                    <p class="car-model"> {{ $carBid['car']->make }} {{ $carBid['car']->model }} {{ $carBid['car']->year }}</p>
                    <p>at {{ $carBid['bid']->created_at }}</p>
                </div>
            @endforeach
        </div>
    @else
        <div class="no-listings">
            <!-- HTML for when there are no car listings -->
            <p>No bids registered for this car yet!</p>
        </div>
    @endif
@endsection
