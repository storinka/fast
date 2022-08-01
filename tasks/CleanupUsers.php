<?php

namespace App\Tasks;

use App\Meta\DeclareTask;

#[DeclareTask(1)]
class CleanupUsers
{
    public function handle(): void
    {
        $deletedUsers = db()->table('users')->delete()->run();

        echo static::class . " | " . date("c") . " |  Users deleted: " . $deletedUsers . "\n";
    }
}
