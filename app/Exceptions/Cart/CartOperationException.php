<?php

namespace App\Exceptions\Cart;

use RuntimeException;

class CartOperationException extends RuntimeException
{
    public function __construct(
        string $message,
        public readonly int $status = 400,
    ) {
        parent::__construct($message);
    }
}
