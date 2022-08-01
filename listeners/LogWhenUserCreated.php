<?php

namespace App\Listeners;

use App\Events\UserCreated;

class LogWhenUserCreated
{
    public function handle(UserCreated $userCreated): void
    {
        echo "User created: {id={$userCreated->user->getId()}}\n";
    }
}
