<?php

namespace App\utils\rules;

use App\Entity\categories;
use Framework\Rules\Rules;
use Framework\Doctrine\EntityManager;

class ruleManga
{
    public function __construct(bool $updateManga)
    {
        $this->rules = [
            'title' => [
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
            'description' => [
                [
                    'rule' => 'required',
                ],
                [
                    'rule' => 'length',
                    'args' => [
                        'max' => 1000,
                        'min' => 10,
                    ],
                ],
            ],
            'categories' => [
                [
                    'rule' => 'required',
                ],
            ],
            'image' => [
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
            'price' => [
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
            'stock' => [
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

        if ($updateManga) {
            $this->rules['newCategorie'] = [
                [
                    'rule' => 'uniq',
                    'args' => [
                        'callFunction' => 'isUniqueCategorie',
                        'object' => $this,
                        'errorMessage' => 'this categorie is already exist',
                    ],
                ],
            ];
        }
    }

    public function isValidateManga($data)
    {
        $ru = new Rules($this->rules);

        return $ru->validate($data);
    }

    public function isUniqueCategorie($data)
    {
        foreach ($data as $value) {
            if (
                EntityManager::getRepository(categories::class)->findOneByTitle(
                    $value
                ) !== null
            ) {
                return false;
            }
        }
    }
}
