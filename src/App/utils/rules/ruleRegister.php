<?php

namespace App\utils\rules;

use Framework\Doctrine\EntityManager;
use App\Entity\User;
use Framework\Rules\Rules;

class ruleRegister
{
    public function __construct()
    {
        $this->rules = [
            'username' => [
                [
                    'rule' => 'required',
                ],

                [
                    'rule' => 'length',
                    'args' => [
                        'max' => 20,
                    ],
                ],
            ],
            'password' => [
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
            'password_confirm' => [
                [
                    'rule' => 'sameAs',
                    'args' => [
                        'field' => 'password',
                        'errorMessage' => 'The password had to be the same !',
                    ],
                ],
            ],
            'firstName' => [
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
            'lastName' => [
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
            'email' => [
                [
                    'rule' => 'required',
                ],

                [
                    'rule' => 'length',
                    'args' => [
                        'max' => 100,
                    ],
                ],
                [
                    'rule' => 'uniq',
                    'args' => [
                        'callFunction' => 'isUniqEmail',
                        'object' => $this,
                        'errorMessage' => 'this email is already used',
                    ],
                ],
            ],
            'address' => [
                [
                    'rule' => 'required',
                ],

                [
                    'rule' => 'length',
                    'args' => [
                        'max' => 255,
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
                        'max' => 255,
                    ],
                ],
            ],
            'zipcode' => [
                [
                    'rule' => 'required',
                ],

                [
                    'rule' => 'length',
                    'args' => [
                        'max' => 5,
                    ],
                ],
            ],
        ];
    }

    public function isValidateRegister($data)
    {
        $ru = new Rules($this->rules);

        return $ru->validate($data);
    }

    public function isUniqEmail($data)
    {
        return EntityManager::getRepository(User::class)->isUniqEmail($data);
    }
}
