<?php

namespace App\Actions\Api;

use App\Models\Faq;

class UpdateFaqAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(Faq $faq, array $data): Faq
    {
        $faq->update($data);

        return $faq;
    }
}
