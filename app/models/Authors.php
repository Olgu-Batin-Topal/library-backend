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
        $query = "SELECT * FROM books b 
                  WHERE b.author_id = :authorId";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':authorId', $authorId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
