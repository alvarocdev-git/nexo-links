<?php

namespace App\Http\Requests;

use App\Rules\LinkUrl;
use Illuminate\Foundation\Http\FormRequest;

class StoreLinkRequest extends FormRequest
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
        return [
            'title' => ['required', 'string', 'max:120'],
            'url' => ['required', 'string', 'max:2048', new LinkUrl],
        ];
    }
}
