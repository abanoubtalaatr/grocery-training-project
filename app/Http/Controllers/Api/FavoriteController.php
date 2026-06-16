<?php

namespace App\Http\Controllers\Api;

use App\Http\Actions\Api\Favorite\CheckFavoriteAction;
use App\Http\Actions\Api\Favorite\GetFavoritesAction;
use App\Http\Actions\Api\Favorite\RemoveFavoriteAction;
use App\Http\Actions\Api\Favorite\ToggleFavoriteAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\FavoriteResource;
use App\Http\Resources\Api\FavoriteStatusResource;
use App\Models\Favorite;
// use App\Models\Meal;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;

class FavoriteController extends Controller
{
    use ApiResponse;

    public function index(
        Request $request,
        GetFavoritesAction $action
    ): JsonResponse {

        $favorites = $action->execute(
            $request->user()
        );

        return $this->successResponse(
            'Favorites retrieved successfully',
            [
                'items'
                    => FavoriteResource::collection(
                        $favorites
                    ),

                'total_count'
                    => $favorites->count(),
            ]
        );
    }

    public function store(
        Request $request,
        string $mealId,
        ToggleFavoriteAction $action
    ): JsonResponse {

        $result = $action->execute(
            $request->user(),
            $mealId
        );

        return $this->successResponse(
            $result['message'],
            new FavoriteStatusResource(
                $result
            )
        );
    }

    public function show(
        Request $request,
        string $mealId,
        CheckFavoriteAction $action
    ): JsonResponse {

        return $this->successResponse(
            'Favorite status retrieved',
            new FavoriteStatusResource(
                $action->execute(
                    $request->user(),
                    $mealId
                )
            )
        );
    }

    public function destroy(
        Request $request,
        string $mealId,
        RemoveFavoriteAction $action
    ): JsonResponse {

        return $this->successResponse(
            'Removed from favorites',
            new FavoriteStatusResource(
                $action->execute(
                    $request->user(),
                    $mealId
                )
            )
        );
    }
}