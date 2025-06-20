<?php
require_once __DIR__ . '/../../helpers/BaseModel.php';

class Books extends BaseModel
{
    protected $table = 'books';
    protected $fillable = [
        'author_id',
        'category_id',
        'title',
        'isbn',
        'publication_year',
        'page_count',
        'is_available'
    ];

    public function __construct($requests = [])
    {
        parent::__construct($requests);
    }

    public function search($query)
    {
        $sql = "SELECT * FROM {$this->table} WHERE title LIKE :query OR isbn LIKE :query";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':query', '%' . $query . '%');
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
