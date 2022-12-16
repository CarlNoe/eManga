<?php

namespace Framework\Rules;

class Rules
{
    private array $rules;
    private array $formErrors;

    public function __construct(array $rules)
    {
        $this->rules = $rules;
        $this->formErrors = [];
    }

    public function getRules(): array
    {
        return $this->rules;
    }

    public function getRule(string $key): array
    {
        return $this->rules[$key];
    }

    public function getFormErrors(): array
    {
        return $this->formErrors;
    }

    public function getFormError(string $key): string
    {
        return $this->formErrors[$key];
    }

    public function validate(array $data): array
    {
        foreach ($this->rules as $fieldName => $ruleConfigs) {
            foreach ($ruleConfigs as $ruleConfig) {
                $validationArgs = $ruleConfig['args'] ?? [];
                if (!is_callable([$this, $ruleConfig['rule']])) {
                    throw new \Exception(
                        'Rule ' . $ruleConfig['rule'] . ' is not callable'
                    );
                }

                if (is_string($data[$fieldName])) {
                    $data[$fieldName] = trim($data[$fieldName]);
                }

                $fieldErrors = call_user_func(
                    [$this, $ruleConfig['rule']],
                    $data,
                    $data[$fieldName],
                    ...$validationArgs
                );

                if (!empty($fieldErrors)) {
                    $this->formErrors[$fieldName] = $fieldErrors;
                }
            }
        }
        return $this->formErrors;
    }

    public function required(array $data, string|array $value): array
    {
        if (empty($value) || !isset($value)) {
            return ['This field is required'];
        }
        return [];
    }

    public function length(
        array $data,
        string $value,
        int $min = null,
        int $max = null
    ): array {
        $errors = [];
        if (strlen($value) < $min) {
            $errors[] = 'This field must be at least ' . $min . ' characters';
        }
        if (strlen($value) > $max) {
            $errors[] = 'This field must be at most ' . $max . ' characters';
        }
        return $errors;
    }

    public function email(array $data, string $value): array
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return ['This field must be a valid email'];
        }
        return [];
    }

    function uniq(
        array $data,
        $fieldValue,
        object $object,
        string $callFunction,
        string $errorMessage = 'Entité non unique !'
    ): array {
        $errors = [];

        if (!call_user_func([$object, $callFunction], $fieldValue)) {
            $errors[] = $errorMessage;
        }

        return $errors;
    }

    function sameAs(
        array $data,
        string $fieldValue,
        string $field,
        string $errorMessage = 'Ce champs n\'est pas valide !'
    ) {
        $errors = [];

        if ($data[$field] !== $fieldValue) {
            $errors[] = $errorMessage;
        }

        return $errors;
    }

    function checkbox(
        array $data,
        string $fieldValue,
        bool $checked,
        string $errorMessage = 'Valeur invalide'
    ) {
        $errors = [];

        $fieldChecked = in_array($fieldValue, ['1', 'on']);

        if ($fieldChecked !== $checked) {
            $errors[] = $errorMessage;
        }

        return $errors;
    }

    function numeric(
        array $data,
        string $fieldValue,
        string $errorMessage = 'Only numbers are allowed'
    ) {
        $errors = [];

        if (!is_numeric($fieldValue)) {
            $errors[] = $errorMessage;
        }

        return $errors;
    }
}
