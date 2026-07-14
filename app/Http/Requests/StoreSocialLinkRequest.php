<?php

namespace App\Http\Requests;

use App\Rules\LinkUrl;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSocialLinkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->page !== null;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $platforms = config('nexo.social_platforms');
        $type = $platforms[$this->input('platform')]['type'] ?? null;

        return [
            'platform' => [
                'required',
                Rule::in(array_keys($platforms)),
                Rule::unique('social_links', 'platform')
                    ->where('page_id', $this->user()?->page?->id),
            ],
            'value' => [
                'required',
                'string',
                'max:255',
                ...match ($type) {
                    'email' => ['email'],
                    'phone' => ['regex:/^\+[1-9][0-9]{6,14}$/'],
                    'url' => [new LinkUrl],
                    default => ['regex:/^[A-Za-z0-9][A-Za-z0-9._-]{0,99}$/'],
                },
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'platform.unique' => __('You already added this platform.'),
            'value.regex' => match (config('nexo.social_platforms.'.$this->input('platform').'.type')) {
                'phone' => __('Use the international format, e.g. +5491122334455.'),
                default => __('Use your handle without the @, e.g. alvarocdev.'),
            },
        ];
    }
}
