<?php

namespace App\Actions\Api;

use App\Models\Faq;

class CreateFaqAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): Faq
    {
        return Faq::create($data);
    }
}
