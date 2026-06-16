<?php

namespace App\Actions\Invoice;

use App\Jobs\SendEmailForInvoice;
use App\Models\User;

class SendInvoiceAction
{
    /**
     * Dispatch the job to send an invoice email.
     *
     * @param User $user
     * @return void
     */
    public function __invoke(User $user): void
    {
        SendEmailForInvoice::dispatch($user);
    }
}
