<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDesignRequest extends FormRequest
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
        $hexColor = 'regex:/^#[0-9a-f]{6}$/i';

        return [
            'bio' => ['nullable', 'string', 'max:160'],
            'theme' => ['required', Rule::in(array_keys(config('nexo.themes')))],
            'background_type' => ['required', Rule::in(['default', 'solid', 'gradient'])],
            'background_start' => ['nullable', 'required_if:background_type,solid,gradient', $hexColor],
            'background_end' => ['nullable', 'required_if:background_type,gradient', $hexColor],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'banner' => ['nullable', 'image', 'max:4096'],
            'remove_avatar' => ['sometimes', 'boolean'],
            'remove_banner' => ['sometimes', 'boolean'],
        ];
    }
}
