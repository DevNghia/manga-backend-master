<?php

namespace App\Requests\Authentication;

use App\Requests\ApiRequest;

class VerifyAccountRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to mVerifyAccountRequest $requestake this request.
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
    public function rules()
    {
        return [
            'email' => 'required',
            'token' => 'required',
        ];
    }
}
