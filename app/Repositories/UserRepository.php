<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function allWithRoles()
    {
        return User::with('roles')->latest()->get();
    }

    public function findWithRoles($id)
    {
        return User::with('roles')->findOrFail($id);
    }

    public function find($id)
    {
        return User::findOrFail($id);
    }
}