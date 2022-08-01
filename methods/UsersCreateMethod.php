<?php

namespace App\Methods;

use App\Data\User\UserInput;
use App\Data\User\UserResult;
use App\Entities\User;
use App\Meta\DeclareMethod;
use Invoke\Method;

#[DeclareMethod("users.create")]
class UsersCreateMethod extends Method
{
    protected function handle(UserInput $user): UserResult
    {
        $newUser = new User();
        $newUser->setId(1);
        $newUser->setName($user->name);
        $newUser->save();

        return UserResult::from($newUser);
    }
}
