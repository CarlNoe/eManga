<?php

namespace App\utils\rules;

use App\Entity\categories;
use Framework\Rules\Rules;

class ruleAddress
{
    public function __construct()
    {
        $this->rules = [
            'street' => [
                [
                    'rule' => 'required',
                ],
                [
                    'rule' => 'length',
                    'args' => [
                        'max' => 100,
                    ],
                ],
            ],
            'city' => [
                [
                    'rule' => 'required',
                ],
                [
                    'rule' => 'length',
                    'args' => [
                        'max' => 100,
                    ],
                ],
            ],
            'zipCode' => [
                [
                    'rule' => 'required',
                ],
                [
                    'rule' => 'length',
                    'args' => [
                        'max' => 100,
                    ],
                ],
            ],
            'country' => [
                [
                    'rule' => 'required',
                ],
                [
                    'rule' => 'length',
                    'args' => [
                        'max' => 100,
                    ],
                ],
            ],
        ];
    }

    public function isValidateAdress($data)
    {
        $ru = new Rules($this->rules);

        return $ru->validate($data);
    }
}
