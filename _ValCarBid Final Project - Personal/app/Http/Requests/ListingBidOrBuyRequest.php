<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Listing;

class ListingBidOrBuyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $listingId = request('listing-id');

        $listing = Listing::find($listingId);
        $carBidPrice = $listing->bid_price;
        $carBuyPrice = $listing->buy_price;

        return [
            'new-bid' => ['nullable', 'integer', "gt:$carBidPrice", "lt:$carBuyPrice"],
        ];
    }

    public function messages(): array
    {
        return [
            'new-bid' => [
                'integer' => 'The new bid field must be integer.',
                'gt' => 'The new bid price must be greater than the old bid.',
                'lt' => "The new bid price must be less than the car's buy now price",
            ],
        ];
    }
}
