<?php

namespace App\Services;

use App\Exceptions\JsonException;
use App\Models\LoyaltyAccount;


class AccountService
{

    public function create(array $request)
    {

        validator($request, [
            'name' => ['required', 'string'],
            'email' => ['required', 'unique:loyalty_account', 'email'],
            'card' => ['required', 'unique:loyalty_account'],
            'phone' => ['required', 'unique:loyalty_account'],
        ])->validate();

        return LoyaltyAccount::create($request);
    }

    public function setActivationStatus(string $type, int $id, $status = true)
    {
        $user = $this->findUser($type, $id);

        if ($user->active != $status) {
            $user->active = $status;
            $user->save();
            return $user;
        }

        $status = $user->active ? 'activated' : 'deactivated';

        throw new JsonException("Status already {$status}", 400);
    }

    public function getBalance(string $type, int $id)
    {
        $user = $this->findUser($type, $id);
        return $user->getBalance();
    }

    public function findUser(string $type, int $id)
    {
        $account = LoyaltyAccount::where($type, $id)->first();

        if ($account) {
            return $account;
        }

        throw new JsonException('Account is not found', 400);
    }
}
