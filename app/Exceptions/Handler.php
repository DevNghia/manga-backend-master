<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use League\OAuth2\Server\Exception\OAuthServerException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        \Laravel\Passport\Exceptions\OAuthServerException::class,
        OAuthServerException::class,
        ApiErrorException::class,
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception|Throwable
     */
    public function report(Throwable $exception)
    {
        $this->sendErrorToSentry($exception);

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  Request  $request
     * @param  \Throwable  $exception
     * @return Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if (!config('app.debug', false)) {
            return response()->json(
                [
                    'code' => ($exception->getCode() === 401 ? 401 : 400),
                    'message' => $exception->getMessage(),
                    'errors' => null
                ]
            );
        }

        return parent::render($request, $exception);
    }

    /**
     * @param Throwable $exception
     * Send exception to Sentry
     */
    private function sendErrorToSentry(Throwable $exception)
    {
        // If exception is instance of ApiErrorException, we will ignore it
        // because it is well-known and handled exception
        if (!($exception instanceof ApiErrorException) && !($exception instanceof OAuthServerException)) {
            if ($this->shouldReport($exception) && app()->bound('sentry')) {
                app('sentry')->captureException($exception);
            }
        }
    }
}
