@extends('layouts.layout')
@section('doc_scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src='{{ asset('js/create-file-trigger.js') }}' defer></script>
    <script src='{{ asset('js/create-crash-description-trigger.js') }}' defer></script>
    <script src='{{ asset('js/create-car-type-trigger.js') }}' defer></script>
    <script src='{{ asset('js/create-car-make-model-year-data.js') }}' defer></script>
@endsection
@section('doc_title', 'Create Car')
@section('doc_body')
    <form action="{{ route('listing.create') }}" enctype="multipart/form-data" method="POST" id="car-create-form"
        class="create-form">
        @csrf
        <div class="create-form-parts-container">
            <div class="create-form-part1">
                <!-- Make -->
                <div class="make label-select-group">
                    <label for="make">Make:</label>
                    <select name="make" id="make" class="form-control">
                        <option value="">Select Make</option>
                        @foreach ($makes as $makeOption)
                            <option value="{{ $makeOption }}" {{ old('make') == $makeOption ? 'selected' : '' }}>
                                {{ $makeOption }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('make')
                    <p class="error">{{ $message }}</p>
                @enderror

                <!-- Model -->
                <div class="model label-select-group">
                    <label for="model">Model:</label>
                    <select name="model" id="model" class="form-control">
                        <option value="">Select Model</option>
                        @foreach ($models as $modelOption)
                            <option value="{{ $modelOption }}" {{ old('model') == $modelOption ? 'selected' : '' }}>
                                {{ $modelOption }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('model')
                    <p class="error">{{ $message }}</p>
                @enderror

                <!-- Year -->
                <div class="year label-select-group">
                    <label for="year">Year:</label>
                    <select name="year" id="year" class="form-control">
                        <option value="">Select Year</option>
                        @foreach ($years as $yearOption)
                            <option value="{{ $yearOption }}" {{ old('year') == $yearOption ? 'selected' : '' }}>
                                {{ $yearOption }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('year')
                    <p class="error">{{ $message }}</p>
                @enderror

                <!-- Type -->
                <div class="type label-select-group">
                    <label for="type">Type:</label>
                    <select name="type" id="type">
                        @foreach ($typeOptions as $value => $optionLabel)
                            <option value="{{ $value }}" @if (old('type') == $value) selected @endif>
                                {{ $optionLabel }}</option>
                        @endforeach
                    </select>
                </div>
                @error('type')
                    <p class="error">{{ $message }}</p>
                @enderror

                <!-- Body -->
                <div class="body label-select-group">
                    <label for="body">Body:</label>
                    <select name="body" id="body">
                        @foreach ($bodyOptions as $value => $optionLabel)
                            <option value="{{ $value }}" @if (old('body') == $value) selected @endif>
                                {{ $optionLabel }}</option>
                        @endforeach
                    </select>
                </div>
                @error('body')
                    <p class="error">{{ $message }}</p>
                @enderror

                <!-- Mileage -->
                <input type="number" name="mileage" placeholder="Mileage (mi)" step="0.01" required
                    value="{{ old('mileage') }}">
                @error('mileage')
                    <p class="error">{{ $message }}</p>
                @enderror

                <!-- VIN -->
                <input type="text" name="vin" placeholder="VIN" required value="{{ old('vin') }}">
                @error('vin')
                    <p class="error">{{ $message }}</p>
                @enderror
                @foreach ($errors->get('vin.*') as $error)
                    <p class="error">{{ $error[0] }}</p>
                @endforeach

                <!-- Cylinders -->
                <div class="cylinders label-select-group">
                    <label for="cylinders">Cylinders:</label>
                    <select name="cylinders" id="cylinders">
                        @foreach ($iceCylindersOptions as $value => $optionLabel)
                            <option value="{{ $value }}" @if (old('cylinder') == $value) selected @endif>
                                {{ $optionLabel }}</option>
                        @endforeach
                    </select>
                </div>
                @error('cylinders')
                    <p class="error">{{ $message }}</p>
                @enderror

                <!-- Engine Power -->
                <input type="number" name="engine-power" placeholder="Engine Power (hp)" required min="50"
                    value="{{ old('engine-power') }}">
                @error('engine-power')
                    <p class="error">{{ $message }}</p>
                @enderror

                <!-- Displacement -->
                <input type="number" name="displacement" placeholder="Displacement (l)" step="0.1" required
                    value="{{ old('displacement') }}" min="0.8" max="12">
                @error('displacement')
                    <p class="error">{{ $message }}</p>
                @enderror

                <!-- Battery Capacity -->
                <input type="number" name="battery-capacity" placeholder="Battery Capacity (kWh)" min="10"
                    step="0.1" required value="{{ old('battery-capacity') }}">
                @error('battery-capacity')
                    <p class="error">{{ $message }}</p>
                @enderror

                <!-- Transmission Type -->
                <div class="transmission-type label-select-group">
                    <label for="transmission-type">Transmission Type:</label>
                    <select name="transmission-type" id="transmission-type">
                        @foreach ($iceTransmissionTypeOptions as $value => $optionLabel)
                            <option value="{{ $value }}" @if (old('transmission-type') == $value) selected @endif>
                                {{ $optionLabel }}</option>
                        @endforeach
                    </select>
                </div>
                @error('transmission-type')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="create-form-part2">
                <!-- Drive Type -->
                <div class="drive-type label-select-group">
                    <label for="drive-type">Drive Type:</label>
                    <select name="drive-type" id="drive-type">
                        @foreach ($driveTypeOptions as $value => $optionLabel)
                            <option value="{{ $value }}" @if (old('drive-type') == $value) selected @endif>
                                {{ $optionLabel }}</option>
                        @endforeach
                    </select>
                </div>
                @error('drive-type')
                    <p class="error">{{ $message }}</p>
                @enderror

                <!-- Fuel Type -->
                <div class="fuel-type label-select-group">
                    <label for="fuel-type">Fuel Type:</label>
                    <select name="fuel-type" id="fuel-type">
                        @foreach ($iceFuelTypeOptions as $value => $optionLabel)
                            <option value="{{ $value }}" @if (old('fuel-type') == $value) selected @endif>
                                {{ $optionLabel }}</option>
                        @endforeach
                    </select>
                </div>
                @error('fuel-type')
                    <p class="error">{{ $message }}</p>
                @enderror

                <!-- Door Count -->
                <div class="door-count label-select-group">
                    <label for="door-count">Door count:</label>
                    <select name="door-count" id="door-count">
                        @foreach ($doorCountOptions as $value => $optionLabel)
                            <option value="{{ $value }}" @if (old('door-count') == $value) selected @endif>
                                {{ $optionLabel }}</option>
                        @endforeach
                    </select>
                </div>
                @error('door-count')
                    <p class="error">{{ $message }}</p>
                @enderror

                <!-- Capacity -->
                <div class="capacity label-select-group">
                    <label for="capacity">Capacity:</label>
                    <select name="capacity" id="capacity">
                        @foreach ($capacityOptions as $value => $optionLabel)
                            <option value="{{ $value }}" @if (old('capacity') == $value) selected @endif>
                                {{ $optionLabel }}</option>
                        @endforeach
                    </select>
                </div>
                @error('capacity')
                    <p class="error">{{ $message }}</p>
                @enderror

                <!-- Crashes -->
                <div class="crashes label-select-group">
                    <label for="crashes">Crashes:</label>
                    <select name="crashes" id="crashes">
                        @foreach ($crashesOptions as $value => $optionLabel)
                            <option value="{{ $value }}" @if (old('crashes') == $value) selected @endif>
                                {{ $optionLabel }}</option>
                        @endforeach
                    </select>
                </div>
                @error('crashes')
                    <p class="error">{{ $message }}</p>
                @enderror

                <!-- Crash Description -->
                <input type="text" name="crash-description" id="crash-description" placeholder="Crash Description"
                    required value="{{ old('crash-description') }}">
                @error('crash-description')
                    <p class="error">{{ $message }}</p>
                @enderror

                <!-- Bid Price -->
                <input type="number" name="bid-price" placeholder="Starting Bid Price ($)" step="0.1" required
                    value="{{ old('bid-price') }}">
                @error('bid-price')
                    <p class="error">{{ $message }}</p>
                @enderror

                <!-- Buy Price -->
                <input type="number" name="buy-price" placeholder="Buy Price ($)" step="0.1" required
                    value="{{ old('buy-price') }}">
                @error('buy-price')
                    <p class="error">{{ $message }}</p>
                @enderror

                <!-- Days To Sell -->
                <div class="days-to-sell label-select-group">
                    <label for="days-to-sell">Days To Sell:</label>
                    <select name="days-to-sell" id="days-to-sell">
                        @foreach ($daysToSellOptions as $value => $optionLabel)
                            <option value="{{ $value }}" @if (old('days-to-sell') == $value) selected @endif>
                                {{ $optionLabel }}</option>
                        @endforeach
                    </select>
                </div>
                @error('days-to-sell')
                    <p class="error">{{ $message }}</p>
                @enderror

                <!-- Main Image -->
                <div class="main-image">
                    <label for="file-main-image" id="main-image-label">Choose your car's main image</label>
                    <input type="file" name="main-image" id="file-main-image">
                </div>
                @error('main-image')
                    <p class="error">{{ $message }}</p>
                @enderror

                <!-- Other Images -->
                <div class="images">
                    <label for="file-images" id="file-label">Choose your car images</label>
                    <input type="file" name="images[]" id="file-images" multiple>
                </div>
                @error('images')
                    <p class="error">{{ $message }}</p>
                @enderror
                @foreach ($errors->get('images.*') as $error)
                    <p class="error">{{ $error[0] }}</p>
                @endforeach
            </div>
        </div>
        <button type="submit" class="create-button">Create</button>
    </form>
    <script>
        window.oldInput = {
            fuelType: "{{ old('fuel-type') }}",
            cylinders: "{{ old('cylinders') }}",
            transmissionType: "{{ old('transmission-type') }}",
            crashDescription: "{{ old('crash-description') }}"
        };
    </script>
@endsection
