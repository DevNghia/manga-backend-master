<?php

namespace App\Requests\Authentication;

use App\Requests\ApiRequest;

class SignUpRequest extends ApiRequest
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
    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required|email',
            'required' => [
                'min:8',
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[-@#!$%^&*()_+|~=`{}\[\]:";\'<>?,.\/]/', // must contain a special character
                'max: 50'
            ],
        ];
    }
}
