<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class ApiErrorException extends Exception
{
    /**
     * Create a new authentication exception.
     *
     * @param string $message
     * @param int $code
     */
    public function __construct(string $message = 'Unauthenticated.', int $code = 500)
    {
        parent::__construct($message, $code);
    }

    /**
     * @return JsonResponse
     */
    public function render(): JsonResponse
    {
        return response()->json(
            [
                'code' => $this->code,
                'message' => $this->message,
                'errors' => null
            ]
        );
    }
}
