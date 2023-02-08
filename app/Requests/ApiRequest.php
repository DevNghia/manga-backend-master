<?php

namespace App\Requests;

use App\Helpers\Traits\ResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class ApiRequest extends FormRequest
{
    use ResponseTrait;

    public function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException($this->error(__('general.validation_error'), $validator->errors(), 422));
    }
}
