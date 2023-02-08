<?php

namespace App\Requests\Manga;

use App\Requests\ApiRequest;

class FavoriteRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'is_favorite' => 'required|boolean',
        ];
    }
}

