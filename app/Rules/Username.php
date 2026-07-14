<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Username implements ValidationRule
{
    /**
     * Lowercase letters and digits, optionally separated by single hyphens
     * or underscores; must start and end with a letter or digit.
     */
    private const string FORMAT = '/^[a-z0-9]+(?:[-_][a-z0-9]+)*$/';

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || preg_match(self::FORMAT, $value) !== 1) {
            $fail('The :attribute may only contain lowercase letters, numbers, hyphens and underscores.')->translate();

            return;
        }

        if (in_array($value, config('nexo.reserved_usernames'), true)) {
            $fail('The :attribute ":input" is reserved.')->translate();
        }
    }
}
