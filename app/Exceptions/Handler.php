<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
            if ($exception instanceof ValidationException) {
                return response()->json([
                    'error' => 'Validation Error',
                    'message' => $exception->validator->errors(),
                ], 400);
            }

            if ($exception instanceof ModelNotFoundException) {
                return response()->json([
                    'error' => 'Not Found',
                    'message' => 'The requested resource was not found',
                ], 404);
            }

            if ($exception instanceof AuthenticationException) {
                return response()->json([
                    'error' => 'Unauthenticated',
                    'message' => 'You need to be logged in to access this resource',
                ], 401);
            }

            if ($exception instanceof AuthorizationException) {
                return response()->json([
                    'error' => 'Forbidden',
                    'message' => 'You are not allowed to access this resource',
                ], 403);
            }
            $code=$exception->getCode();
            if($code==0) {
                $code = method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : 500;
            }

            return response()->json([
                'error' => 'Other Error',
                'message' => $exception->getMessage(),
            ], $code);

    }

}
