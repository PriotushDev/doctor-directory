<?php

namespace App\Http\Controllers\Api;

use App\Services\UserService;
use App\Http\Resources\UserResource;
use App\Http\Requests\UpdateUserRoleRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    public function __construct(private UserService $userService)
    {
        $this->middleware(['auth:sanctum', 'role:admin']);
    }

    public function index()
    {
        return UserResource::collection(
            $this->userService->getAllUsers()
        );
    }

    public function show($id)
    {
        return new UserResource(
            $this->userService->getUserById($id)
        );
    }

    public function updateRole(UpdateUserRoleRequest $request, $id)
    {
        return new UserResource(
            $this->userService->updateRole($id, $request->role)
        );
    }

    public function getPermissions()
    {
        // Return all available permissions in the system
        return response()->json([
            'data' => Permission::all()
        ]);
    }

    public function syncPermissions(Request $request, $id)
    {
        $request->validate([
            'permissions' => 'array'
        ]);

        return new UserResource(
            $this->userService->syncPermissions($id, $request->permissions ?? [])
        );
    }

    public function destroy($id)
    {
        $this->userService->deleteUser($id);

        return response()->json([
            'message' => 'User deleted successfully'
        ]);
    }
}
