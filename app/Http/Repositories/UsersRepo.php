<?php

namespace App\Http\Repositories;

use App\Models\User;

class UsersRepo
{
    public function getFirst()
    {
        return User::whereNull('deleted_at')->first();
    }

    public function getAll()
    {
        return User::all();
    }
}
