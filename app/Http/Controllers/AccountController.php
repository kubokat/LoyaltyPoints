<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountRequest;
use App\Mail\AccountActivated;
use App\Mail\AccountDeactivated;
use App\Models\LoyaltyAccount;
use App\Services\AccountService;
use App\Services\NotifyService;
use Illuminate\Http\Request;

class AccountController extends Controller
{

    private $accountService;
    private $notifyService;

    public function __construct(AccountService $accountService, NotifyService $notifyService)
    {
        $this->accountService = $accountService;
        $this->notifyService = $notifyService;
    }

    public function create(Request $request)
    {
        return $this->accountService->create($request->all());
    }

    public function activate(AccountRequest $request, $type, $id)
    {
        $user = $this->accountService->setActivationStatus($type, $id);

        $this->notifyService->notify(
            $user,
            new AccountActivated($user->getBalance()),
            'Account restored'
        );

        return response()->json(['success' => true]);
    }

    public function deactivate(AccountRequest $request, $type, $id)
    {
        $user = $this->accountService->setActivationStatus($type, $id, 0);
        $this->notifyService->notify($user, new AccountDeactivated(), 'Account banned');
        return response()->json(['success' => true]);
    }

    public function balance(AccountRequest $request, $type, $id)
    {
        $balance = $this->accountService->getBalance($type, $id);
        return response()->json(['balance' => $balance], 400);
    }
}
