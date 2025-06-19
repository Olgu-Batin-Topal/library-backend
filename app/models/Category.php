<?php
require_once __DIR__ . '/../../helpers/BaseModel.php';

class Category extends BaseModel
{
    protected $table = 'categories';
    protected $fillable = [
        'name',
        'description'
    ];
}
