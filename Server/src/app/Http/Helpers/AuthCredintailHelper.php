<?php

namespace App\Http\Helpers;

use App\Models\User;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthCredintailHelper
{
  public static function credintails($token, $remember = true): array
  {
    $user = User::find( auth('api')->user()->id );
    if(!$user) {
      throw new HttpException(404, 'Не удалось найти пользователя');
    }

    return [
      'access_token' => $token,
      'token_type' => 'bearer',
      'user' => $user,
      'expires_in' => auth('api')->factory()->getTTL() * (int)($remember ? 24*30 : 60)
    ];
  }
}