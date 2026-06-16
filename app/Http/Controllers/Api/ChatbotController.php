<?php

namespace App\Http\Controllers\Api;

use App\Actions\Chatbot\GetSuggestionsAction;
use App\Actions\Chatbot\SendChatQuestionAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ChatRequest;
use App\Http\Resources\Api\V1\ChatMessageResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    use ApiResponse;

    public function chat(ChatRequest $request, SendChatQuestionAction $action): JsonResponse
    {
        $message = $action->execute($request->user(), $request->toActionData());

        return $this->successResponse(
            data: new ChatMessageResource($message),
            message: 'Chat response generated successfully'
        );
    }


    public function history(Request $request): JsonResponse
    {
        $messages = $request->user()->chatbotMessages()->orderBy('created_at', 'desc')->paginate(
            min(max((int) $request->input('per_page', 15), 1), 50)
        );

        return $this->successResponse(
            data: ChatMessageResource::collection($messages)->response()->getData(true)['data'],
            message: 'Chat history retrieved successfully'
        );
    }

 
    public function suggestions(Request $request, GetSuggestionsAction $action): JsonResponse
    {
        return $this->successResponse(
            data: ['suggestions' => $action->execute($request->input('locale', 'en'))],
            message: 'Suggestions retrieved successfully'
        );
    }
}