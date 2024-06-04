<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\State;
use App\Models\City;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**xa
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        $properNounRegex = "regex:/^([A-Z][a-z]*|Mc[A-Z][a-z]*)(?:[.\-\s]{1,2}[A-Z][a-z]*)*$/";

        $stateValidationRule = function ($attribute, $value, $fail) {
            if (!State::where('id', $value)->exists()) {
                $fail('The selected state is invalid.');
            }
        };

        $cityValidationRule = function ($attribute, $value, $fail) use ($input) {
            if (!City::where('id', $value)
                ->where('state_id', $input['state'])
                ->exists()) {
                $fail('The selected city is invalid or does not match the state.');
            }
        };

        // !!!!!new mail regex: 
        // ^(?:[a-zA-Z0-9._%+-]+@(?:gmail\.com|outlook\.com|yahoo\.com|aol\.com|icloud\.com|protonmail\.com|zoho\.com|gmx\.com|yandex\.com|mail\.com|mail\.ru|tutanota\.com|fastmail\.com|hushmail\.com|runbox\.com))$

        Validator::make($input, [
            'name' => ['required', 'min:3', 'max:255', $properNounRegex],
            'surname' => ['required', 'min:3', 'max:255', $properNounRegex],
            'email' => ['required', 'min:10', 'max:255', 'regex:/^(.+)@(.+)\.(.+)$/', 'unique:users'],
            'phone' => ['required', 'regex:/^[1-9]\d{9}$/', 'unique:users'],
            'state' => ['required', $stateValidationRule],
            'city' => ['required', $cityValidationRule],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ],
        [
            "name.required" => "Please provide your name.",
            "name.min" => "Your name must be at least 3 characters long.",
            "name.max" => "Your name cannot exceed 255 characters.",
            "name.regex" => "Your name must start with an uppercase letter, followed by lowercase letters, and may include periods, or spaces between words.",
            
            "surname.required" => "Please provide your surname.",
            "surname.min" => "Your surname must be at least 3 characters long.",
            "surname.max" => "Your surname cannot exceed 255 characters.",
            "surname.regex" => "Your surname must start with an uppercase letter, followed by lowercase letters, and may include periods, or spaces between words.",
            
            "email.required" => "Please enter your email address.",
            "email.min" => "Your email address must be at least 10 characters long.",
            "email.max" => "Your email address cannot exceed 255 characters.",
            "email.regex" => "Please enter a valid email address.",
            "email.unique" => "This email has already been registered.",
            
            "phone.required" => "Please provide your phone number.",
            "phone.regex" => "Phone number must consist of exactly 10 digits.",
            "phone.unique" => "This phone number has already been registered.",
            
            "state.required" => "Please provide your state.",
            
            "city.required" => "Please provide your city.",
            
            "password.required" => "Please enter a password.",
            
            "terms.accepted" => "You must accept the terms and conditions.",
            "terms.required" => "Please accept the terms and conditions.",
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'surname' => $input['surname'],
            'email' => $input['email'],
            'phone' => $input['phone'],
            'state' => $input['state'],
            'city' => $input['city'],
            'password' => Hash::make($input['password']),
        ]);
    }
}
