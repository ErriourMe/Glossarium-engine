<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;

use Illuminate\Support\Arr;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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

    public function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        if($e instanceof ValidationException && $request->expectsJson()) {
            return response()->json([
                "status" => false,
                "messages" => [
                    [
                        'title' => 'Form error',
                        'text' => 'You have errors in form',
                        'icon' => 'warning',
                    ]
                ],
                "validation" =>  $e->errors()
            ], 422);
        } else {
            parent::convertValidationExceptionToResponse($e, $request);
        }
    }

    public function convertExceptionToArray(Throwable $e)
    {
        $data = json_decode($e->getMessage(), JSON_OBJECT_AS_ARRAY) ?? $e->getMessage();
        $result['title'] = isset($data['title']) ? $data['title'] : 'Ошибка системы';
        $result['text'] = isset($data['text']) ? $data['text'] : $data;
        $result['icon'] = 'warning';

        return config('app.debug') ? [
            'messages' => [ $result ],
            'exception' => get_class($e),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => collect($e->getTrace())->map(function ($trace) {
                return Arr::except($trace, ['args']);
            })->all(),
        ] : [
            'success' => false,
            'messages' => [ $result ],
        ];
    }
}
