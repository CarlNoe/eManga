<?php

namespace App\utils;

use Framework\Doctrine\EntityManager;
use App\Entity\User;

class ruleRegister
{
    protected array $errors = [];
    protected array $data = [];

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->errors = [];
    }

    public function validate(): void
    {
        $this->validateEmail();
        $this->validatePassword();
        $this->validatePasswordConfirm();
        $this->validateUsername();
        $this->validateFirstname();
        $this->validateLastname();
        $this->validateCity();
        $this->validateZipcode();
        $this->validateAddress();
        $this->isUniqueEmail();
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    protected function validateEmail(): void
    {
        if (empty($this->data['email'])) {
            $this->errors['email'] = 'Email is required';
        } elseif (!filter_var($this->data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = 'Email is not valid';
        }
    }

    protected function validatePassword(): void
    {
        if (empty($this->data['password'])) {
            $this->errors['password'] = 'Password is required';
        } elseif (strlen($this->data['password']) < 8) {
            $this->errors['password'] =
                'Password must be at least 8 characters';
        }
    }

    protected function validatePasswordConfirm(): void
    {
        if (empty($this->data['password_confirm'])) {
            $this->errors['password_confirm'] =
                'Password confirmation is required';
        } elseif ($this->data['password'] !== $this->data['password_confirm']) {
            $this->errors['password_confirm'] =
                'Password confirmation must be the same as password';
        }
    }

    protected function validateUsername(): void
    {
        if (empty($this->data['username'])) {
            $this->errors['username'] = 'Username is required';
        } elseif (strlen($this->data['username']) < 3) {
            $this->errors['username'] =
                'Username must be at least 3 characters';
        }
    }

    protected function validateFirstname(): void
    {
        if (empty($this->data['firstname'])) {
            $this->errors['firstname'] = 'Firstname is required';
        } elseif (strlen($this->data['firstname']) < 3) {
            $this->errors['firstname'] =
                'Firstname must be at least 3 characters';
        }
    }

    protected function validateLastname(): void
    {
        if (empty($this->data['lastname'])) {
            $this->errors['lastname'] = 'Lastname is required';
        } elseif (strlen($this->data['lastname']) < 3) {
            $this->errors['lastname'] =
                'Lastname must be at least 3 characters';
        }
    }

    protected function validateCity(): void
    {
        if (empty($this->data['city'])) {
            $this->errors['city'] = 'City is required';
        } elseif (strlen($this->data['city']) < 3) {
            $this->errors['city'] = 'City must be at least 3 characters';
        }
    }

    protected function validateZipcode(): void
    {
        if (empty($this->data['zipcode'])) {
            $this->errors['zipcode'] = 'Zipcode is required';
        } elseif (strlen($this->data['zipcode']) < 3) {
            $this->errors['zipcode'] = 'Zipcode must be at least 3 characters';
        }
    }

    protected function validateAddress(): void
    {
        if (empty($this->data['address'])) {
            $this->errors['address'] = 'Address is required';
        } elseif (strlen($this->data['address']) < 3) {
            $this->errors['address'] = 'Address must be at least 3 characters';
        }
    }

    protected function isUniqueEmail(): void
    {
        if (!empty($this->data['email'])) {
            $em = EntityManager::getInstance();
            $userRepository = $em->getRepository(User::class);
            $user = $userRepository->findOneByEmail($this->data['email']);
            $user
                ? ($this->errors['email'] = 'This email is already used')
                : null;
        }
    }
}
