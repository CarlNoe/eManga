<?php

namespace Framework\Exception;

class PaymentAmountMissmatchException extends \Exception
{
    public function __construct(int $value, int $expectedValue)
    {
        parent::__construct(
            sprintf(
                'The payment amount is not correct. Expected %d, got %d',
                $expectedValue,
                $value
            )
        );
    }
}
