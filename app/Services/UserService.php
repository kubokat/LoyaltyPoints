<?php

namespace App\Services;

use App\Exceptions\JsonException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function create(array $request)
    {
        validator($request, [
            'name' => ['required', 'string'],
        ])->validate();

        return User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);
    }

    public function login(array $request)
    {
        $user = User::where('email', $request['email'])->first();

        if ($user && Hash::check($request['password'], $user->password)) {
            return ['user' => $user, 'token' => $this->createToken($user)];
        }

        throw new JsonException('Bad credentials', 401);
    }

    public function createToken(User $user)
    {
        return $user->createToken('apiToken')->plainTextToken;
    }
}
