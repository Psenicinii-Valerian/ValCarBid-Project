<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class DifferentOriginalName implements Rule
{
    private $otherFile;

    public function __construct($otherFile)
    {
        $this->otherFile = $otherFile;
    }

    public function passes($attribute, $value)
    {
        // Check if the originalName of the two files is different
        return $value->getClientOriginalName() !== $this->otherFile->getClientOriginalName();
    }

    public function message()
    {
        return 'You cannot include your main car image here.';
    }
}
