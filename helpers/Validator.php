<?php
require_once __DIR__ . '/../config/db.php';

class Validator
{
    private $data;
    private $rules;
    private $errors = [];

    public function __construct($data, $rules)
    {
        $this->data = $data;
        $this->rules = $rules;
        $this->validate();
    }

    private function validate()
    {
        global $db;

        foreach ($this->rules as $field => $ruleStr) {
            $rules = explode('|', $ruleStr);
            foreach ($rules as $rule) {

                // Required
                if ($rule === 'required' && empty($this->data[$field])) {
                    $this->errors[$field][] = "$field alanı zorunludur.";
                }

                // Email
                if ($rule === 'email' && !filter_var($this->data[$field] ?? '', FILTER_VALIDATE_EMAIL)) {
                    $this->errors[$field][] = "$field geçerli bir e-posta olmalıdır.";
                }

                // Unique
                if (str_starts_with($rule, 'unique:')) {
                    $explode = explode(',', str_replace('unique:', '', $rule));
                    match (count($explode)) {
                        2 => [$table, $column, $id] = array_merge($explode, [null]),
                        3 => [$table, $column, $id] = $explode,
                        default => throw new Exception("Geçersiz unique kuralı: $rule"),
                    };

                    $value = $this->data[$field] ?? null;
                    if (empty($value)) {
                        $this->errors[$field][] = "$field alanı zorunludur.";
                        continue;
                    }

                    if ($table && $column && $value) {
                        $stmt = $db->prepare("SELECT COUNT(*) FROM $table WHERE $column = :value");
                        $stmt->bindValue(':value', $value);
                        $stmt->execute();

                        $count = $stmt->fetchColumn();

                        if ($count > 0) {
                            $this->errors[$field][] = "$field zaten kullanılıyor.";
                        }
                    }
                }

                // Max
                if (str_starts_with($rule, 'max:')) {
                    $maxLength = (int) str_replace('max:', '', $rule);

                    if (isset($this->data[$field]) && strlen($this->data[$field]) > $maxLength) {
                        $this->errors[$field][] = "$field en fazla $maxLength karakter olmalıdır.";
                    }
                }
            }
        }
    }

    public function fails()
    {
        return !empty($this->errors);
    }

    public function errors()
    {
        return $this->errors;
    }
}
