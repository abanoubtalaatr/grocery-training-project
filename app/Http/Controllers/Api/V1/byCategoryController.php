<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\FaqResource;
use App\Models\Faq;
use App\Traits\ResponseApi;
use Illuminate\Http\Request;

class byCategoryController extends Controller
{
    //

  use ResponseApi;
    public function __invoke($category){
        $faqs = Faq::active()
            ->category($category)
            ->ordered()
            ->get();

        return FaqResource::collection($faqs);
    }
}
