<?php

namespace App\Methods;

use App\Data\User\UserResult;
use App\Entities\User;
use App\Meta\DeclareMethod;
use Invoke\Method;

#[DeclareMethod("users.get")]
class UsersGetMethod extends Method
{
    protected function handle(): array
    {
        $users = User::select()->fetchAll();

        return UserResult::many($users);
    }
}
