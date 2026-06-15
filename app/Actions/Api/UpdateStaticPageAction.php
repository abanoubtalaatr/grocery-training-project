<?php

namespace App\Actions\Api;

use App\Models\StaticPage;

class UpdateStaticPageAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(StaticPage $staticPage, array $data): StaticPage
    {
        $staticPage->update($data);

        return $staticPage;
    }
}
