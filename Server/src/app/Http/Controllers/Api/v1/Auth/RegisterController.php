<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Auth\RegisterController\RegisterRequest;
use App\Services\RegisterService;

class RegisterController extends Controller
{
    protected $registerService;

    public function __construct(RegisterService $registerService)
    {
        $this->registerService = $registerService;
    }

    public function register(RegisterRequest $request)
    {
        $credintails = $this->registerService->register($request);

        return response()->json([
            'success' => true,
            'messages' => [
                [
                    'title' => 'Привет, '. $credintails['user']['name'],
                    'text' => 'Вы зарегистрировались в системе',
                    'duration' => 30000,
                ],
            ],
            'data' => [
                'credintails' => $credintails,
            ],
        ], 200);
    }
}
