<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\State;
use App\Models\City;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, string>  $input
     */
    public function update(User $user, array $input)
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

        Validator::make($input, [
            'name' => ['required', 'min:3', 'max:255', $properNounRegex],
            'surname' => ['required', 'min:3', 'max:255', $properNounRegex],
            'email' => ['required', 'min:10', 'max:255', 'regex:/^(.+)@(.+)\.(.+)$/', Rule::unique('users')->ignore($user->id)],
            'phone' => ['required', 'regex:/^[1-9]\d{9}$/', Rule::unique('users')->ignore($user->id)],
            'state' => ['required', $stateValidationRule],
            'city' => ['required', $cityValidationRule],
        ],
        [
            "name.required" => "Please provide your name.",
            "name.min" => "Your name must be at least 3 characters long.",
            "name.max" => "Your name cannot exceed 255 characters.",
            "name.regex" => "Your name must start with an uppercase letter, followed by lowercase letters, and may include periods, hyphens, or spaces between words.",
            
            "surname.required" => "Please provide your surname.",
            "surname.min" => "Your surname must be at least 3 characters long.",
            "surname.max" => "Your surname cannot exceed 255 characters.",
            "surname.regex" => "Your surname must start with an uppercase letter, followed by lowercase letters, and may include periods, hyphens, or spaces between words.",
            
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
        ])->validate();

        // Verify Name and Surname Change
        if ($input['name'] !== $user->name || $input['surname'] !== $user->surname) {
            // Generate the new storage directory based on the updated name and surname
            $newStorageDirectory = "uploads/{$input['name']} {$input['surname']}";

            // Check if the new storage directory already exists
            if (!Storage::disk('public')->exists($newStorageDirectory)) {
                // Create the new storage directory if it doesn't exist
                Storage::disk('public')->makeDirectory($newStorageDirectory);

                // Get the old storage directory based on the user's name and surname
                $oldStorageDirectory = "uploads/{$user->name} {$user->surname}";

                // Move user's files from the old directory to the new directory
                $images = Storage::disk('public')->allFiles($oldStorageDirectory);

                $newImages = [];
                foreach ($images as $image) {
                    $relativePath = str_replace($oldStorageDirectory, '', $image);
                    $newPath = "{$newStorageDirectory}{$relativePath}";
                    $newImages[] = $newPath;
                }

                foreach ($images as $key => $oldImage) {
                    $newImage = $newImages[$key];
                    Storage::disk('public')->move($oldImage, $newImage);
                }

                // Delete the old storage directory and its contents
                Storage::disk('public')->deleteDirectory($oldStorageDirectory);
            }
        }

        if ($input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input);
        } else {
            try {
                $user->forceFill([
                    'name' => $input['name'],
                    'surname' => $input['surname'],
                    'email' => $input['email'],
                    'phone' => $input['phone'],
                    'state' => $input['state'],
                    'city' => $input['city'],
                ])->save();
            } catch (\Exception $e) {
                dd($e->getMessage());
            }
            return redirect("/user/profile");
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, string>  $input
     */
    protected function updateVerifiedUser(User $user, array $input)
    {
        try {
            $user->forceFill([
                'name' => $input['name'],
                'surname' => $input['surname'],
                'email' => $input['email'],
                'phone' => $input['phone'],
                'state' => $input['state'],
                'city' => $input['city'],
            ])->save();
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
        
        $user->sendEmailVerificationNotification();

        return redirect("/user/profile");
    }
}
