<?php

namespace App\Events;

use App\Entities\User;

class UserCreated
{
    public function __construct(public User $user)
    {
    }
}
