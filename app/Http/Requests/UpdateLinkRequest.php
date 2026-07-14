<?php

namespace App\Http\Requests;

use App\Models\Link;
use App\Rules\LinkUrl;
use Illuminate\Foundation\Http\FormRequest;

class UpdateLinkRequest extends FormRequest
{
    public function authorize(): bool
    {
        $link = $this->route('link');

        return $link instanceof Link && $this->user()?->can('update', $link) === true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:120'],
            'url' => ['sometimes', 'required', 'string', 'max:2048', new LinkUrl],
            'is_visible' => ['sometimes', 'boolean'],
            'is_highlighted' => ['sometimes', 'boolean'],
            'show_countdown' => ['sometimes', 'boolean'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after:starts_at'],
        ];
    }
}
