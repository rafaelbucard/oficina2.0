<?php

namespace App\Core;

/**
 * Validador simples baseado em regras por campo.
 * Regras suportadas: required, email, numeric, integer, min:n, max:n,
 * in:a,b,c, same:campo.
 */
class Validator
{
    private array $errors = [];

    public function __construct(
        private array $data,
        private array $rules,
        private array $labels = []
    ) {
    }

    public static function make(array $data, array $rules, array $labels = []): self
    {
        $validator = new self($data, $rules, $labels);
        $validator->run();
        return $validator;
    }

    private function run(): void
    {
        foreach ($this->rules as $field => $ruleset) {
            $rules = is_array($ruleset) ? $ruleset : explode('|', $ruleset);
            $value = $this->data[$field] ?? null;

            foreach ($rules as $rule) {
                [$name, $arg] = array_pad(explode(':', $rule, 2), 2, null);
                $this->apply($field, $value, $name, $arg);
            }
        }
    }

    private function apply(string $field, mixed $value, string $rule, ?string $arg): void
    {
        $label = $this->labels[$field] ?? $field;
        $str   = is_string($value) ? trim($value) : $value;

        switch ($rule) {
            case 'required':
                if ($str === null || $str === '' || $str === []) {
                    $this->add($field, "O campo {$label} é obrigatório.");
                }
                break;

            case 'email':
                if ($str !== null && $str !== '' && !filter_var($str, FILTER_VALIDATE_EMAIL)) {
                    $this->add($field, "Informe um e-mail válido para {$label}.");
                }
                break;

            case 'numeric':
                if ($str !== null && $str !== '' && !is_numeric(str_replace(',', '.', (string) $str))) {
                    $this->add($field, "O campo {$label} deve ser numérico.");
                }
                break;

            case 'integer':
                if ($str !== null && $str !== '' && filter_var($str, FILTER_VALIDATE_INT) === false) {
                    $this->add($field, "O campo {$label} deve ser um número inteiro.");
                }
                break;

            case 'min':
                if ($str !== null && $str !== '' && mb_strlen((string) $str) < (int) $arg) {
                    $this->add($field, "O campo {$label} deve ter ao menos {$arg} caracteres.");
                }
                break;

            case 'max':
                if ($str !== null && mb_strlen((string) $str) > (int) $arg) {
                    $this->add($field, "O campo {$label} deve ter no máximo {$arg} caracteres.");
                }
                break;

            case 'in':
                $options = explode(',', (string) $arg);
                if ($str !== null && $str !== '' && !in_array($str, $options, true)) {
                    $this->add($field, "Valor inválido para {$label}.");
                }
                break;

            case 'same':
                if (($this->data[$arg] ?? null) !== $value) {
                    $other = $this->labels[$arg] ?? $arg;
                    $this->add($field, "{$label} deve ser igual a {$other}.");
                }
                break;
        }
    }

    private function add(string $field, string $message): void
    {
        $this->errors[$field][] = $message;
    }

    public function fails(): bool
    {
        return $this->errors !== [];
    }

    public function errors(): array
    {
        return $this->errors;
    }

    /** Lista achatada de mensagens para exibição. */
    public function messages(): array
    {
        $flat = [];
        foreach ($this->errors as $messages) {
            foreach ($messages as $message) {
                $flat[] = $message;
            }
        }
        return $flat;
    }
}
