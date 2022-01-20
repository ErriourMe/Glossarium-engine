<?php

namespace App\Services;

use App\Models\User;
use App\Http\Helpers\AuthCredintailHelper;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RegisterService
{  
  public function __construct(
    protected User $user
  ){}

  public function register(object $request): array
  {
    $user = $this->user->create($request->validated());
        
    if (!$token = auth('api')->login($user)) {
      throw new HttpException(401, 'Не удается войти в систему');
    }

    return AuthCredintailHelper::credintails($token);
  }
}