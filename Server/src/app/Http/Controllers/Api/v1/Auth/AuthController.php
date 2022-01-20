<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Auth\AuthController\LoginRequest;
use App\Services\AuthService;

class AuthController extends Controller
{
  protected $authService;

  public function __construct(AuthService $authService)
  {
    $this->authService = $authService;
  }

  public function login(LoginRequest $request)
  {
    $credintails = $this->authService->login($request);

    return response()->json([
      'success' => true,
      'messages' => [
        [
          'title' => 'Привет, '. $credintails['user']['name'],
          'text' => 'Вы успешно вошли в систему',
          // 'icon' => 'login',
          'image' => '/static/images/webp/no-avatar.webp',
          'duration' => 30000,
        ]
      ],
      'data' => [
        'credintails' => $credintails,
      ],
    ], 200);
  }

  public function me()
  {
    $credintails = $this->authService->me();

    return response()->json([
      'success' => true,
      'data' => [
        'credintails' => $credintails,
      ],
    ], 200);
  }
}
