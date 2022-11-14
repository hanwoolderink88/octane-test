<?php

namespace App\Exceptions;

use Throwable;
use Termwind\Components\Dd;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
        //
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
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        // Define the response
        $response = [
            'message' => config('app.debug') ? $e->getMessage() : 'An error occurred',
        ];

        if ($e instanceof ValidationException) {
            $response['message'] = 'The given data was invalid.';
        }

        if (method_exists($e, 'errors')) {
            $response['errors'] = $e->errors();
        }

        // If the app is in debug mode
        if (config('app.debug')) {
            // Add the exception class name, message and stack trace to response
            $response['exception'] = get_class($e); // Reflection might be better here
            $response['trace'] = array_map(function ($trace) {
                unset($trace['args']);
                unset($trace['type']);

                return $trace;
            }, $e->getTrace());
        }

        $status = 500;
        if (isset($e->status)) {
            $status = $e->status;
        } elseif (method_exists($e, 'getCode')) {
            $status = $e->getCode();
        }

        // If this exception is an instance of HttpException
        if ($this->isHttpException($e)) {
            // Grab the HTTP status code from the Exception
            $status = $e->getStatusCode();
        }

        $status = $status === 0 ? 500 : $status;

        // Return a JSON response with the response array and status code
        return response()->json($response, $status);
    }
}
