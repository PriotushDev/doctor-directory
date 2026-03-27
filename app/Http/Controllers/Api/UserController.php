<?php

namespace App\Http\Controllers\Api;

use App\Services\UserService;
use App\Http\Resources\UserResource;
use App\Http\Requests\UpdateUserRoleRequest;
use App\Http\Controllers\Controller;

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

    public function destroy($id)
    {
        $this->userService->deleteUser($id);

        return response()->json([
            'message' => 'User deleted successfully'
        ]);
    }
}
