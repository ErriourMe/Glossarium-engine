<?php

namespace App\Services;

use App\Http\Helpers\AuthCredintailHelper;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class AuthService
 * @package App\Services
 */
class AuthService
{
  public function __construct(
    protected User $user
  ){}

  public function login(object $request): array
  {
    $user = User::whereEmail($request->email)->first();

    if(!$user || !Hash::check($request->password, $user->password)) {
      throw new HttpException(422, json_encode([
        'title' => 'Ошибка входа',
        'text' => 'Неверные логин или пароль',
      ]));
    }

    $token = auth('api')->attempt(
      $request->only([
        'email',
        'password',
      ])
    );

    if (!$token) {
      throw new HttpException(401, json_encode([
        'title' => 'Ошибка входа',
        'text' => 'Неверные логин или пароль'
      ]));
    }

    return AuthCredintailHelper::credintails($token);
  }

  public function me(): array
  {
    $user = auth('api')->user();
    if(!$user) {
      throw new HttpException(401, json_encode([
        'title' => 'Ошибка входа',
        'text' => 'Вы не вошли в систему'
      ]));
    }

    return AuthCredintailHelper::credintails(auth('api')->tokenById($user->id));
  }
}
