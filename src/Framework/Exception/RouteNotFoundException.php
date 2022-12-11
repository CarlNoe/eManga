<?php

namespace Framework\Exception;

use Exception;

class RouteNotFoundException extends Exception
{
    public function __construct(
        string $message = '',
        int $code = 0,
        Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        return __CLASS__ .
            ": [{$this->code}]: {$this->message} in {$this->file} on line {$this->line} ";
    }

    public function customFunction()
    {
        echo "A custom function for this type of exception \n";
    }
}
