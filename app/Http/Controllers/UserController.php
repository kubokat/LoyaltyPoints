<?php
namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $service)
    {
        $this->userService = $service;
    }

    public function register(UserRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = $this->userService->create($request->all());
        $token = $this->userService->createToken($user);
        return response()->json(['user' => $user, 'token' => $token], 201);
    }

    public function login(UserRequest $request): \Illuminate\Http\JsonResponse
    {
        $user_with_token = $this->userService->login($request->all());
        return response()->json($user_with_token, 201);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out'], 201);
    }
}
