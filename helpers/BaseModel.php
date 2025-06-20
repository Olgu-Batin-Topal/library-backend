<?php
require_once __DIR__ . '/../config/db.php';

class BaseModel
{
    protected $db;
    protected $table = '';
    protected $fillable = [];
    protected $requests = [];

    /**
     * BaseModel constructor.
     */
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

    /**
     * Get all records from the table.
     */
    public function all()
    {
        $sql = sprintf("SELECT * FROM %s", $this->table);
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get all records with pagination.
     */
    public function allWithPagination($page = 1, $limit = 10)
    {
        $offset = ($page - 1) * $limit;
        $sql = sprintf("SELECT * FROM %s LIMIT :limit OFFSET :offset", $this->table);
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Count the total number of records in the table.
     */
    public function count()
    {
        $sql = sprintf("SELECT COUNT(*) FROM %s", $this->table);
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        $count = $stmt->fetchColumn();

        return $count ? (int)$count : 0;
    }

    /**
     * Find a record by its ID.
     */
    public function find($id)
    {
        $sql = sprintf("SELECT * FROM %s WHERE id = :id", $this->table);
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Create a new record in the table.
     */
    public function create()
    {
        try {
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
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Update a record by its ID.
     */
    public function update($id)
    {
        try {
            $fields = $this->requests;

            $setClause = implode(', ', array_map(fn($col) => "$col = :$col", array_keys($fields)));

            $sql = sprintf(
                "UPDATE %s SET %s WHERE id = :id",
                $this->table,
                $setClause
            );

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':updated_at', date('Y-m-d H:i:s'));
            foreach ($fields as $key => $value) {
                $stmt->bindValue(':' . $key, $value);
            }

            return $stmt->execute();
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Delete a record by its ID.
     */
    public function delete($id)
    {
        try {
            $sql = sprintf(
                "DELETE FROM %s WHERE id = :id",
                $this->table
            );

            $stmt = $this->db->prepare($sql);

            $stmt->bindValue(':id', $id, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
}
