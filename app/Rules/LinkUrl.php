<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class LinkUrl implements ValidationRule
{
    /**
     * Schemes a link may use. Anything else (javascript:, data:, file:, ...)
     * is rejected to keep public pages safe.
     */
    private const array ALLOWED_SCHEMES = ['http', 'https', 'mailto', 'tel'];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            $fail('The :attribute must be a valid URL.');

            return;
        }

        $scheme = strtolower((string) parse_url($value, PHP_URL_SCHEME));

        if (! in_array($scheme, self::ALLOWED_SCHEMES, true)) {
            $fail('The :attribute must start with http://, https://, mailto: or tel:.');

            return;
        }

        if (in_array($scheme, ['http', 'https'], true) && filter_var($value, FILTER_VALIDATE_URL) === false) {
            $fail('The :attribute must be a valid URL.');
        }
    }
}
