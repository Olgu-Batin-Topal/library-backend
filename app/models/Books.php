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
}
