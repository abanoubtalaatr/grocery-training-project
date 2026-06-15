<?php

namespace App\Actions\Api;

use App\Models\StaticPage;
use Illuminate\Support\Collection;

class ListImportantStaticPagesAction
{
    public function execute(): Collection
    {
        return StaticPage::published()
            ->whereIn('slug', ['terms-and-conditions', 'policies', 'about-us', 'contact-us'])
            ->ordered()
            ->get(['slug', 'title']);
    }
}
