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
/*
    protected array $errors = [];
    protected array $data = [];

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->errors = [];
    }

    public function validate(): void
    {
        $this->validateTitle();
        $this->validateDescription();
        $this->validateCategorie();
        $this->validateImage();
        $this->validatePrice();
        $this->validateStock();
        $this->isUniqueCategorie();
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    protected function validateTitle(): void
    {
        if (empty($this->data['title'])) {
            $this->errors['title'] = 'Title is required';
        }
    }

    protected function validateDescription(): void
    {
        if (empty($this->data['description'])) {
            $this->errors['descrition'] = 'description is required';
        } elseif (strlen($this->data['description']) < 10) {
            $this->errors['description'] =
                'description must be at least 10 characters';
        }
    }

    protected function validateCategorie(): void
    {
        if (empty($this->data['categories'])) {
            $this->errors['categories'] = 'Categorie is required';
        }
    }

    protected function validateImage(): void
    {
        if (empty($this->data['image'])) {
            $this->errors['image'] = 'Image is required';
        }
    }

    protected function validatePrice(): void
    {
        if (empty($this->data['price'])) {
            $this->errors['price'] = 'Price is required';
        } elseif (!is_numeric($this->data['price'])) {
            $this->errors['price'] = 'Price must be a number';
        }
    }

    protected function validateStock(): void
    {
        if (empty($this->data['stock'])) {
            $this->errors['stock'] = 'Stock is required';
        } elseif (!is_numeric($this->data['stock'])) {
            $this->errors['stock'] = 'Stock must be a number';
        }
    }

    protected function isUniqueCategorie(): void
    {
        foreach ($this->data['categories'] as $categorie) {
            $checkCategorie = EntityManager::getInstance()
                ->getRepository(Categories::class)
                ->findOneByTitle($categorie);
            if ($checkCategorie) {
                $this->errors['name'] =
                    'Categorie ' .
                    $checkCategorie->getName() .
                    ' already exists';
            }
        }
    }*/
