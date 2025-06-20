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
                        $stmt = $db->prepare("SELECT count(*) FROM $table WHERE $column = :value" . (!is_null($id) ? " AND id != :id" : ""));
                        $stmt->bindValue(':value', strtolower($value), PDO::PARAM_STR);

                        if (!is_null($id)) {
                            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
                        }

                        try {
                            $stmt->execute();
                        } catch (PDOException $e) {
                            $this->errors[$field][] = "Veritabanı hatası: " . $e->getMessage();
                            continue;
                        }

                        var_dump($stmt->debugDumpParams());

                        $count = $stmt->fetchColumn();

                        if ($count > 0) {
                            $this->errors[$field][] = "$field zaten kullanılıyor.";
                        }
                    } else {
                        $this->errors[$field][] = "Geçersiz unique kuralı: $rule";
                        continue;
                    }
                }

                // Max
                if (str_starts_with($rule, 'max:')) {
                    $maxLength = (int) str_replace('max:', '', $rule);

                    if (isset($this->data[$field]) && strlen($this->data[$field]) > $maxLength) {
                        $this->errors[$field][] = "$field en fazla $maxLength karakter olmalıdır.";
                    }
                }

                // Min
                if (str_starts_with($rule, 'min:')) {
                    $minLength = (int) str_replace('min:', '', $rule);

                    if (isset($this->data[$field]) && strlen($this->data[$field]) < $minLength) {
                        $this->errors[$field][] = "$field en az $minLength karakter olmalıdır.";
                    }
                }

                // Integer
                if ($rule === 'integer' && isset($this->data[$field]) && !is_numeric($this->data[$field])) {
                    $this->errors[$field][] = "$field tam sayı olmalıdır.";
                }

                // Boolean
                if ($rule === 'boolean' && isset($this->data[$field]) && !in_array($this->data[$field], [0, 1, true, false], true)) {
                    $this->errors[$field][] = "$field boolean (0 veya 1) olmalıdır.";
                }

                // Exists
                if (str_starts_with($rule, 'exists:')) {
                    $explode = explode(',', str_replace('exists:', '', $rule));
                    match (count($explode)) {
                        2 => [$table, $column] = $explode,
                        default => throw new Exception("Geçersiz exists kuralı: $rule"),
                    };

                    $value = $this->data[$field] ?? null;
                    if (empty($value)) {
                        $this->errors[$field][] = "$field alanı zorunludur.";
                        continue;
                    }

                    if ($table && $column && $value) {
                        $stmt = $db->prepare("SELECT count(*) FROM $table WHERE $column = :value");
                        $stmt->bindValue(':value', $value);

                        try {
                            $stmt->execute();
                        } catch (PDOException $e) {
                            $this->errors[$field][] = "Veritabanı hatası: " . $e->getMessage();
                            continue;
                        }

                        $count = $stmt->fetchColumn();

                        if ($count === 0) {
                            $this->errors[$field][] = "$field geçerli bir kayıt olmalıdır.";
                        }
                    } else {
                        $this->errors[$field][] = "Geçersiz exists kuralı: $rule";
                    }
                }

                // Date format
                if (str_starts_with($rule, 'date_format:')) {
                    $format = str_replace('date_format:', '', $rule);
                    $date = DateTime::createFromFormat($format, $this->data[$field] ?? '');

                    if (!$date || $date->format($format) !== (string)($this->data[$field] ?? '')) {
                        $this->errors[$field][] = "$field geçerli bir tarih formatında olmalıdır: $format.";
                    }
                }

                // ISBN
                if ($rule === 'isbn') {
                    $isbn = $this->data[$field] ?? '';
                    if (empty($isbn)) {
                        $this->errors[$field][] = "$field alanı zorunludur.";
                        continue;
                    }

                    $digits = str_split(preg_replace('/[^\dX]/', '', $isbn));

                    $total = 0;
                    for ($i = 0; $i < count($digits); $i++) {
                        $total += ($i % 2 === 0 ? 1 : 3) * (int)$digits[$i];
                    }

                    if ($total % 10 !== 0) {
                        $this->errors[$field][] = "$field geçerli bir ISBN olmalıdır.";
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
