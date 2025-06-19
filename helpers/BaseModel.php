<?php
require_once __DIR__ . '/../config/db.php';

class BaseModel
{
    protected $db;
    protected $table = '';
    protected $fillable = [];
    protected $requests = [];

    public function __construct($requests = [])
    {
        global $db;
        $this->db = $db;

        foreach ($this->fillable as $field) {
            if (isset($requests[$field])) {
                $this->requests[$field] = $requests[$field];
            }
        }
    }

    public function create()
    {
        $fields = $this->requests;

        $columns = array_keys($fields);
        $placeholders = array_map(fn($col) => ':' . $col, $columns);


        $sql = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            $this->table,
            implode(', ', $columns),
            implode(', ', $placeholders)
        );

        $stmt = $this->db->prepare($sql);

        foreach ($fields as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }

        return $stmt->execute();
    }
}
