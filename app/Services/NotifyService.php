<?php
namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotifyService {

    public function notify($account, $template, $message) {
        if ($this->checkEmail($account)) {
            Mail::to($account)->send($template);
        }

        if ($this->checkPhone($account)) {
            Log::info($message);

        }
    }

    private function checkEmail($account) {
        return $account->email != '' && $account->email_notification;
    }

    private function checkPhone($account) {
        return $account->phone != '' && $account->phone_notification;
    }
}
