<?php

namespace App\Actions\Api;

use App\Models\StaticPage;

class CreateStaticPageAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): StaticPage
    {
        return StaticPage::create($data);
    }
}
