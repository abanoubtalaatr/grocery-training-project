<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class BusinessException extends Exception
{
    protected int $statusCode;
    protected ?array $data;

    public function __construct(string $message, int $statusCode = 400, ?array $data = null)
    {
        parent::__construct($message);
        $this->statusCode = $statusCode;
        $this->data = $data;
    }

    /**
     * Render the exception into an HTTP response.
     */
    public function render($request): JsonResponse
    {
        $payload = [
            'success' => false,
            'message' => $this->getMessage(),
        ];

        if ($this->data !== null) {
            $payload['data'] = $this->data;
        }

        return response()->json($payload, $this->statusCode);
    }
}
