<?php

namespace App\Exceptions;

use Exception;

class AmountToPayValidationException extends Exception
{
    protected $data = [];

    public function __construct(string $message, int $code = 400, array $data = [])
    {
        parent::__construct($message, $code);
        $this->data = $data;
    }
    public function getData(): array
    {
        return $this->data;
    }
    

}
