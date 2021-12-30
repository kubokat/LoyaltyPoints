<?php

namespace App\Services;

use App\Models\LoyaltyPointsTransaction;
use Illuminate\Support\Facades\Log;
use App\Exceptions\JsonException;

class LoyaltyPointsService
{
    public function deposite($account, $data)
    {

        validator($data, [
            'loyalty_points_rule' => ['required', 'int'],
            'description' => ['required', 'string'],
            'points_amount' => ['required'],
            'payment_id' => ['required', 'int']
        ])->validate();

        $this->checkAccountActivity($account);

        return LoyaltyPointsTransaction::performPaymentLoyaltyPoints(
            $account->id,
            $data['loyalty_points_rule'],
            $data['description'],
            $data['payment_id'],
            $data['payment_amount'],
            $data['payment_time']
        );
    }

    public function withdraw($account, $data)
    {

        validator($data, [
            'points_amount' => ['required', 'gt:0'],
        ])->validate();

        Log::info('Withdraw loyalty points transaction input: ' . print_r($data, true));

        $this->checkAccountActivity($account);
        $this->checkFunds($account, $data);

        $transaction = LoyaltyPointsTransaction::withdrawLoyaltyPoints($account->id, $data['points_amount'], $data['description']);
        Log::info($transaction);
        return $transaction;
    }

    public function cancel($data)
    {
        validator($data, [
            'cancellation_reason' => ['required'],
            'transaction_id' => ['required', 'int']
        ])->validate();

        if ($transaction = LoyaltyPointsTransaction::where('id', $data['transaction_id'])->where('canceled', 0)->first()) {
            $transaction->canceled = time();
            $transaction->cancellation_reason = $data['cancellation_reason'];
            $transaction->save();

            return $transaction;
        } else {
            throw new JsonException('Transaction is not found', 400);
        }
    }

    private function checkAccountActivity($account)
    {
        if ($account->active) {
            return $account->active;
        }

        throw new JsonException('Account is not active', 400);
    }

    private function checkFunds($account, $data)
    {
        if ($account->getBalance() < $data['points_amount']) {
            Log::info('Insufficient funds: ' . $data['points_amount']);
            throw new JsonException('Insufficient funds', 400);
        }
    }
}
