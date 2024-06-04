    @extends('layouts.layout')

    @section('doc_scripts')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> --}}
        <script src='{{ asset('js/index-expire-time-trigger.js') }}' defer></script>
        <script src='{{ asset('js/detailed-car-expire-time-trigger.js') }}' defer></script>
        <script src='{{ asset('js/detailed-car-image-carousel.js') }}' defer></script>
        <script src='{{ asset('js/detailed-car-bid-btn-trigger.js') }}' defer></script>
        <script src='{{ asset('js/success-message-trigger.js') }}' defer></script>
    @endsection

    @section('doc_title', 'Car Detailed Info')
    @section('doc_body')
        @if (session('success_msg'))
            <p id="success" class="bid-success-msg"><i class="fa-solid fa-check"></i> {{ session('success_msg') }}</p>
        @endif
        <form action="{{ '/listing/' . $car->id }}" class="detailed-info-form" method="POST">
            @csrf
            <div class="car-detailed">
                <div class="car-image">
                    <img src="{{ asset($images[0]) }}" alt="Car Main Image" data-images='@json(array_map('asset', $images))'>
                    <div class="arrow-btns">
                        <div class="left-arrow"><i class="fa-solid fa-angle-left"></i></div>
                        <div class="right-arrow"><i class="fa-solid fa-angle-right"></i></div>
                    </div>
                </div>
                <div class="car-detailed-info-sections">
                    <div class="car-main-info">
                        <h2>{{ strtoupper($car->make) }} {{ strtoupper($car->model) }}</h2>
                        <p>{{ $car->year }}</p>
                    </div>
                    <div class="car-secondary-info-2xl">
                        <div class="car-detailed-info-section">
                            {{-- 1 --}}
                            <div class="car-detailed-info">
                                <h3>State</h3>
                                <p>{{ $sellerState }}</p>
                            </div>
                            {{-- 2 --}}
                            <div class="car-detailed-info">
                                <h3>City</h3>
                                <p>{{ $sellerCity }}</p>
                            </div>
                            {{-- 3 --}}
                            <div class="car-detailed-info">
                                <h3>Body</h3>
                                @if ($car->body === 'suv')
                                    <p>SUV</p>
                                @else
                                    <p>{{ ucfirst($car->body) }}</p>
                                @endif
                            </div>
                            {{-- 4 --}}
                            <div class="car-detailed-info">
                                <h3>Type</h3>
                                <p>
                                    @if ($car->type === 'ev')
                                        Electric Vehicle
                                    @else
                                        <p>Internal Combustion Engine</p>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="car-detailed-info-section">
                            {{-- 1 --}}
                            <div class="car-detailed-info">
                                <h3>Mileage</h3>
                                <p>{{ $car->mileage }} mi</p>
                            </div>
                            {{-- 2 --}}
                            <div class="car-detailed-info">
                                <h3>VIN</h3>
                                <p>{{ $car->vin }}</p>
                            </div>
                            {{-- 3 --}}
                            <div class="car-detailed-info">
                                <h3>Cylinders</h3>
                                @if (isset($car->cylinders))
                                    <p>{{ $car->cylinders }}</p>
                                @else
                                    <p>-</p>
                                @endif
                            </div>
                            {{-- 4 --}}
                            <div class="car-detailed-info">
                                <h3>Engine Power</h3>
                                <p>{{ $car->engine_power }} hp</p>
                            </div>
                        </div>
                        <div class="car-detailed-info-section">
                            {{-- 1 --}}
                            <div class="car-detailed-info">
                                @if (isset($car->displacement))
                                    <h3>Displacement</h3>
                                    <p>{{ $car->displacement }} l</p>
                                @else
                                    <h3>Battery Capacity</h3>
                                    <p>{{ $car->battery_capacity }} kWh</p>
                                @endif
                            </div>
                            {{-- 2 --}}
                            <div class="car-detailed-info">
                                <h3>Transmission Type</h3>
                                @if ($car->transmission === 'cvt')
                                    <p>Continuously Variable Transmission</p>
                                @else
                                    <p>{{ ucwords($car->transmission_type) }}</p>
                                @endif
                            </div>
                            {{-- 3 --}}
                            <div class="car-detailed-info">
                                <h3>Drive Type</h3>
                                @if ($car->drive_type === 'awd')
                                    <p>All-Wheel Drive</p>
                                @elseif($car->drive_type === 'fwd')
                                    <p>Front-Wheel Drive</p>
                                @elseif($car->drive_type === 'rwd')
                                    <p>Rear-Wheel Drive</p>
                                @else
                                    <p>Four-Wheel Drive</p>
                                @endif
                            </div>
                            {{-- 4 --}}
                            <div class="car-detailed-info">
                                <h3>Fuel Type</h3>
                                <p>{{ ucfirst($car->fuel_type) }}</p>
                            </div>
                        </div>
                        <div class="car-detailed-info-section">
                            {{-- 1 --}}
                            <div class="car-detailed-info">
                                <h3>Door count</h3>
                                <p>{{ $car->door_count }}</p>
                            </div>
                            {{-- 2 --}}
                            <div class="car-detailed-info">
                                <h3>Capacity</h3>
                                <p>{{ $car->capacity }} people</p>
                            </div>
                            {{-- 3 --}}
                            <div class="car-detailed-info">
                                <h3>Crashes</h3>
                                @if ($car->crashes == '1')
                                    <p>Yes</p>
                                @else
                                    <p>No</p>
                                @endif
                            </div>
                            {{-- 4 --}}
                            <div class="car-detailed-info">
                                <h3>Crash Description</h3>
                                @if (isset($car->crash_description))
                                    <p class="crash-description-paragraph">{{ $car->crash_description }}</p>
                                @else
                                    <p>-</p>
                                @endif
                            </div>
                        </div>
                        <div class="car-detailed-info-section">
                            {{-- 1 --}}
                            <div class="car-detailed-info">
                                <div class="bid-price-info">
                                    <h3>Bid Price</h3>
                                    <a href="car-bid-log/{{ $car->id }}" class="circle-info-link">
                                        <i class="fa-solid fa-circle-info"></i>
                                    </a>
                                </div>
                                <p>${{ $listing->bid_price }}</p>
                            </div>
                            {{-- 2 --}}
                            <div class="car-detailed-info">
                                <h3>Buy Price</h3>
                                <p>${{ $listing->buy_price }}</p>
                            </div>
                            {{-- 3 --}}
                            <div class="car-detailed-info">
                                <h3>Current Bid Winner</h3>
                                @if (isset($currentBidWinner))
                                    <p>{{ $currentBidWinner }}</p>
                                @else
                                    <p>-</p>
                                @endif
                            </div>
                            {{-- 4 --}}
                            <div class="car-detailed-info">
                                <h3>Time Left</h3>
                                <p id="expires-in">
                                    {{ sprintf('%02d', $timeRemaining['days']) }}d :
                                    {{ sprintf('%02d', $timeRemaining['hours']) }}h :
                                    {{ sprintf('%02d', $timeRemaining['minutes']) }}m :
                                    {{ sprintf('%02d', $timeRemaining['seconds']) }}s
                                </p>
                            </div>
                        </div>
                    </div>
                    {{-- Content for XL, MD, LG screen --}}
                    <div class="car-secondary-info-xl">
                        <div class="car-detailed-info-section">
                            {{-- 1 --}}
                            <div class="car-detailed-info">
                                <h3>State</h3>
                                <p>{{ $sellerState }}</p>
                            </div>
                            {{-- 2 --}}
                            <div class="car-detailed-info">
                                <h3>City</h3>
                                <p>{{ $sellerCity }}</p>
                            </div>
                            {{-- 3 --}}
                            <div class="car-detailed-info">
                                <h3>Body</h3>
                                @if ($car->body === 'suv')
                                    <p>SUV</p>
                                @else
                                    <p>{{ ucfirst($car->body) }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="car-detailed-info-section">
                            {{-- 1 --}}
                            <div class="car-detailed-info">
                                <h3>Type</h3>
                                <p>
                                    @if ($car->type === 'ev')
                                        Electric Vehicle
                                    @else
                                        <p>Internal Combustion Engine</p>
                                    @endif
                                </p>
                            </div>
                            {{-- 2 --}}
                            <div class="car-detailed-info">
                                <h3>Mileage</h3>
                                <p>{{ $car->mileage }} mi</p>
                            </div>
                            {{-- 3 --}}
                            <div class="car-detailed-info">
                                <h3>VIN</h3>
                                <p>{{ $car->vin }}</p>
                            </div>
                        </div>
                        <div class="car-detailed-info-section">
                            {{-- 1 --}}
                            <div class="car-detailed-info">
                                <h3>Cylinders</h3>
                                @if (isset($car->cylinders))
                                    <p>{{ $car->cylinders }}</p>
                                @else
                                    <p>-</p>
                                @endif
                            </div>
                            {{-- 2 --}}
                            <div class="car-detailed-info">
                                <h3>Engine Power</h3>
                                <p>{{ $car->engine_power }} hp</p>
                            </div>
                            {{-- 3 --}}
                            <div class="car-detailed-info">
                                @if (isset($car->displacement))
                                    <h3>Displacement</h3>
                                    <p>{{ $car->displacement }} l</p>
                                @else
                                    <h3>Battery Capacity</h3>
                                    <p>{{ $car->battery_capacity }} kWh</p>
                                @endif
                            </div>
                        </div>
                        <div class="car-detailed-info-section">
                            {{-- 1 --}}
                            <div class="car-detailed-info">
                                <h3>Transmission Type</h3>
                                @if ($car->transmission === 'cvt')
                                    <p>CVT</p>
                                @else
                                    <p>{{ ucwords($car->transmission_type) }}</p>
                                @endif
                            </div>
                            {{-- 2 --}}
                            <div class="car-detailed-info">
                                <h3>Drive Type</h3>
                                @if ($car->drive_type === 'awd')
                                    <p>All-Wheel Drive</p>
                                @elseif($car->drive_type === 'fwd')
                                    <p>Front-Wheel Drive</p>
                                @elseif($car->drive_type === 'rwd')
                                    <p>Rear-Wheel Drive</p>
                                @else
                                    <p>Four-Wheel Drive</p>
                                @endif
                            </div>
                            {{-- 3 --}}
                            <div class="car-detailed-info">
                                <h3>Fuel Type</h3>
                                <p>{{ ucfirst($car->fuel_type) }}</p>
                            </div>
                        </div>
                        <div class="car-detailed-info-section">
                            {{-- 1 --}}
                            <div class="car-detailed-info">
                                <h3>Door count</h3>
                                <p>{{ $car->door_count }}</p>
                            </div>
                            {{-- 2 --}}
                            <div class="car-detailed-info">
                                <h3>Capacity</h3>
                                <p>{{ $car->capacity }} people</p>
                            </div>
                            {{-- 3 --}}
                            <div class="car-detailed-info">
                                <h3>Crashes</h3>
                                @if ($car->crashes === '1')
                                    <p>Yes</p>
                                @else
                                    <p>No</p>
                                @endif
                            </div>
                        </div>
                        <div class="car-detailed-info-section">
                            {{-- 1 --}}
                            <div class="car-detailed-info">
                                <h3>Crash Description</h3>
                                @if (isset($car->crash_description))
                                    <p>{{ $car->crash_description }}</p>
                                @else
                                    <p>-</p>
                                @endif
                            </div>
                            {{-- 2 --}}
                            <div class="car-detailed-info">
                                <div class="bid-price-info">
                                    <h3>Bid Price</h3>
                                    <a href="car-bid-log/{{ $car->id }}" class="circle-info-link">
                                        <i class="fa-solid fa-circle-info"></i>
                                    </a>
                                </div>
                                <p>${{ $listing->bid_price }}</p>
                            </div>
                            {{-- 3 --}}
                            <div class="car-detailed-info">
                                <h3>Buy Price</h3>
                                <p>${{ $listing->buy_price }}</p>
                            </div>
                        </div>
                        <div class="car-detailed-info-section">
                            {{-- 1 --}}
                            <div class="car-detailed-info">
                                <h3>Current Bid Winner</h3>
                                @if (isset($currentBidWinner))
                                    <p>{{ $currentBidWinner }}</p>
                                @else
                                    <p>-</p>
                                @endif
                            </div>
                            {{-- 2 --}}
                            <div class="car-detailed-info">
                                <h3>Time Left</h3>
                                <p id="expires-in">
                                    {{ sprintf('%02d', $timeRemaining['days']) }}d :
                                    {{ sprintf('%02d', $timeRemaining['hours']) }}h :
                                    {{ sprintf('%02d', $timeRemaining['minutes']) }}m :
                                    {{ sprintf('%02d', $timeRemaining['seconds']) }}s
                                </p>
                            </div>
                        </div>
                    </div>
                    {{-- Content for ESM, SM screen --}}
                    <div class="car-secondary-info-esm">
                        <div class="car-detailed-info-section">
                            {{-- 1 --}}
                            <div class="car-detailed-info">
                                <h3>State</h3>
                                <p>{{ $sellerState }}</p>
                            </div>
                            {{-- 2 --}}
                            <div class="car-detailed-info">
                                <h3>City</h3>
                                <p>{{ $sellerCity }}</p>
                            </div>
                        </div>
                        <div class="car-detailed-info-section">
                            {{-- 1 --}}
                            <div class="car-detailed-info">
                                <h3>Type</h3>
                                <p>
                                    @if ($car->type === 'ev')
                                        Electric Vehicle
                                    @else
                                        <p>Internal Combustion Engine</p>
                                    @endif
                                </p>
                            </div>
                            {{-- 2 --}}
                            <div class="car-detailed-info">
                                <h3>Mileage</h3>
                                <p>{{ $car->mileage }} mi</p>
                            </div>
                        </div>
                        <div class="car-detailed-info-section">
                            {{-- 1 --}}
                            <div class="car-detailed-info">
                                <h3>VIN</h3>
                                <p>{{ $car->vin }}</p>
                            </div>
                            {{-- 2 --}}
                            <div class="car-detailed-info">
                                <h3>Cylinders</h3>
                                @if (isset($car->cylinders))
                                    <p>{{ $car->cylinders }}</p>
                                @else
                                    <p>-</p>
                                @endif
                            </div>
                        </div>
                        <div class="car-detailed-info-section">
                            {{-- 1 --}}
                            <div class="car-detailed-info">
                                <h3>Engine Power</h3>
                                <p>{{ $car->engine_power }} hp</p>
                            </div>
                            {{-- 2 --}}
                            <div class="car-detailed-info">
                                @if (isset($car->displacement))
                                    <h3>Displacement</h3>
                                    <p>{{ $car->displacement }} l</p>
                                @else
                                    <h3>Battery Capacity</h3>
                                    <p>{{ $car->battery_capacity }} kWh</p>
                                @endif
                            </div>
                        </div>
                        <div class="car-detailed-info-section">
                            {{-- 1 --}}
                            <div class="car-detailed-info">
                                <h3>Transmission Type</h3>
                                @if ($car->transmission === 'cvt')
                                    <p>Continuously Variable Transmission</p>
                                @else
                                    <p>{{ ucwords($car->transmission_type) }}</p>
                                @endif
                            </div>
                            {{-- 2 --}}
                            <div class="car-detailed-info">
                                <h3>Drive Type</h3>
                                @if ($car->drive_type === 'awd')
                                    <p>All-Wheel Drive</p>
                                @elseif($car->drive_type === 'fwd')
                                    <p>Front-Wheel Drive</p>
                                @elseif($car->drive_type === 'rwd')
                                    <p>Rear-Wheel Drive</p>
                                @else
                                    <p>Four-Wheel Drive</p>
                                @endif
                            </div>
                        </div>
                        <div class="car-detailed-info-section">
                            {{-- 1 --}}
                            <div class="car-detailed-info">
                                <h3>Fuel Type</h3>
                                <p>{{ ucfirst($car->fuel_type) }}</p>
                            </div>
                            {{-- 2 --}}
                            <div class="car-detailed-info">
                                <h3>Door count</h3>
                                <p>{{ $car->door_count }}</p>
                            </div>
                        </div>
                        <div class="car-detailed-info-section">
                            {{-- 1 --}}
                            <div class="car-detailed-info">
                                <h3>Capacity</h3>
                                <p>{{ $car->capacity }} people</p>
                            </div>
                            {{-- 2 --}}
                            <div class="car-detailed-info">
                                <h3>Crashes</h3>
                                @if ($car->crashes === '1')
                                    <p>Yes</p>
                                @else
                                    <p>No</p>
                                @endif
                            </div>
                        </div>
                        <div class="car-detailed-info-section">
                            {{-- 1 --}}
                            <div class="car-detailed-info">
                                <h3>Crash Description</h3>
                                @if (isset($car->crash_description))
                                    <p>{{ $car->crash_description }}</p>
                                @else
                                    <p>-</p>
                                @endif
                            </div>
                            {{-- 2 --}}
                            <div class="car-detailed-info">
                                <div class="bid-price-info">
                                    <h3>Bid Price</h3>
                                    <a href="car-bid-log/{{ $car->id }}" class="circle-info-link">
                                        <i class="fa-solid fa-circle-info"></i>
                                    </a>
                                </div>
                                <p>${{ $listing->bid_price }}</p>
                            </div>
                        </div>
                        <div class="car-detailed-info-section">
                            {{-- 1 --}}
                            <div class="car-detailed-info">
                                <h3>Buy Price</h3>
                                <p>${{ $listing->buy_price }}</p>
                            </div>
                            {{-- 2 --}}
                            <div class="car-detailed-info">
                                <h3>Current Bid Winner</h3>
                                @if (isset($currentBidWinner))
                                    <p>{{ $currentBidWinner }}</p>
                                @else
                                    <p>-</p>
                                @endif
                            </div>
                        </div>
                        <div class="car-detailed-info-section">
                            {{-- 1 --}}
                            <div class="car-detailed-info">
                                <h3>Time Left</h3>
                                <p id="expires-in">
                                    {{ sprintf('%02d', $timeRemaining['days']) }}d :
                                    {{ sprintf('%02d', $timeRemaining['hours']) }}h :
                                    {{ sprintf('%02d', $timeRemaining['minutes']) }}m :
                                    {{ sprintf('%02d', $timeRemaining['seconds']) }}s
                                </p>
                            </div>
                        </div>
                    </div>
                    @if ($userID !== $car->seller_id)
                        <div class="bid-buy-btns">
                            {{--  Bid --}}
                            <div class="bid-btns">
                                <input type="number" name="new-bid" min="{{ $listing->bid_price + 1 }}"
                                    placeholder="Enter Bid" id="bid-input">
                                <button type="submit" class="bid-btn" id="bid-button" hidden>BID</button>
                                @error('new-bid')
                                    <p class="error">{{ $message }}</p>
                                @enderror
                            </div>
                            {{-- Buy --}}
                            <button type="submit" class="buy-btn">BUY</button>
                            <input type="hidden" name="buy-order" value="{{ $listing->buy_price }}">
                            <input type="hidden" name="car-id" value="{{ $car->id }}">
                            <input type="hidden" name="user-id" value="{{ $userID }}">
                            <input type="hidden" name="seller-id" value="{{ $seller->id }}">
                            <input type="hidden" name="listing-id" value="{{ $listing->id }}">
                        </div>
                    @else
                        <div class="listing-owner">
                            <h3>You're the owner of this listing!</h3>
                        </div>
                    @endif
                </div>
            </div>
        </form>
    @endsection
