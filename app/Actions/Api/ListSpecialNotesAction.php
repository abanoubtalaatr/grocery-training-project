<?php

namespace App\Actions\Api;

use App\Models\SpecialNote;
use Illuminate\Support\Collection;

class ListSpecialNotesAction
{
    public function execute(): Collection
    {
        return SpecialNote::all();
    }
}
