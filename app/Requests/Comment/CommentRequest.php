<?php

namespace App\Requests\Comment;

use App\Requests\ApiRequest;

class CommentRequest extends ApiRequest
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
            'content' => 'required',
            'is_reply' => 'required|boolean',
            'comment_id' => 'required_if:is_reply,==,true',
        ];
    }
}

