<?php

namespace App\Http\Actions\Api\Contact;

use App\Models\ContactMessage;

class UpdateContactStatusAction
{
    public function execute(
        ContactMessage $message,
        array $data
    ): ContactMessage {

        $message->update([
            'status' => $data['status'],
            'admin_notes' =>
                $data['admin_notes']
                ?? null,
        ]);

        return $message->fresh();
    }
}