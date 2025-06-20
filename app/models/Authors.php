<?php
require_once __DIR__ . '/../../helpers/BaseModel.php';

class Authors extends BaseModel
{
    protected $table = 'authors';
    protected $fillable = ['name', 'email'];

    public function __construct($data = [])
    {
        parent::__construct($data);
    }

    public function getBooksByAuthor($authorId)
    {
        $query = "SELECT 
                b.*, 
                a.name AS author_name, 
                c.name AS category_name
              FROM books b
              JOIN authors a ON b.author_id = a.id
              JOIN categories c ON b.category_id = c.id
              WHERE b.author_id = :authorId";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':authorId', $authorId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
