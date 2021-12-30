<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountRequest;
use App\Mail\LoyaltyPointsReceived;
use App\Services\AccountService;
use App\Services\LoyaltyPointsService;
use App\Services\NotifyService;
use Illuminate\Http\Request;

class LoyaltyPointsController extends Controller
{

    private $accountService;
    private $loyalityService;
    private $notifyService;

    public function __construct(
        AccountService $accountService,
        LoyaltyPointsService $loyalityService,
        NotifyService $notifyService
    )
    {
        $this->accountService = $accountService;
        $this->loyalityService = $loyalityService;
        $this->notifyService = $notifyService;
    }

    public function deposit(AccountRequest $request)
    {
        $data = $request->all();
        $account = $this->accountService->findUser($data['type'], $data['id']);
        $transaction = $this->loyalityService->deposite($account, $data);

        $this->notifyService->notify(
            $account,
            new LoyaltyPointsReceived($transaction->points_amount, $account->getBalance()),
            'You received' . $transaction->points_amount . 'Your balance' . $account->getBalance()
        );

        return $transaction;
    }

    public function cancel(Request $request)
    {
        return $this->loyalityService->cancel($request->all());
    }

    public function withdraw(AccountRequest $request)
    {
        $data = $request->all();
        $account = $this->accountService->findUser($data['type'], $data['id']);
        $this->loyalityService->withdraw($account, $data);
    }
}
