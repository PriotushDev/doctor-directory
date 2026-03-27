<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Spatie\Permission\PermissionRegistrar;

class UserService
{
    public function __construct(private UserRepository $repo) {}

    public function getAllUsers()
    {
        return $this->repo->allWithRoles();
    }

    public function getUserById($id)
    {
        return $this->repo->findWithRoles($id);
    }

    public function updateRole($id, $role)
    {
        $user = $this->repo->find($id);

        if ($user->id === auth()->id()) {
            throw new \Exception("You cannot change your own role");
        }

        if ($user->hasRole($role)) {
            throw new \Exception("User already has this role");
        }

        $user->syncRoles([$role]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return $user->load('roles');
    }

    public function deleteUser($id)
    {
        $user = $this->repo->find($id);

        if ($user->id === auth()->id()) {
            throw new \Exception("You cannot delete yourself");
        }

        $user->delete();
    }
}