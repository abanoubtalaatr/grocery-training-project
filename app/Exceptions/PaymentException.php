<?php

namespace App\Exceptions;

use Exception;

class PaymentException extends Exception
{
    public function __construct(
        string $message = '',
        int $code = 400,
        public array $data = []
    ) {
        parent::__construct($message, $code);
    }
}
