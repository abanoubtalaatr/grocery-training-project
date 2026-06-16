<?php 

namespace App\Http\Actions\Api\Contact;



class DetectSpamAction
{
    public function execute(
        string $message
    ): bool {

        $keywords = [
            'viagra',
            'casino',
            'loan',
            'debt',
            'free money',
            'work from home',
            'make money fast',
            'click here',
        ];

        $message = strtolower($message);

        foreach ($keywords as $keyword) {

            if (
                str_contains(
                    $message,
                    $keyword
                )
            ) {
                return true;
            }
        }

        return false;
    }
}