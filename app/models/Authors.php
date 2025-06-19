<?php
require_once __DIR__ . '/../../helpers/BaseModel.php';

class Authors extends BaseModel
{
    protected $table = 'authors';
    protected $fillable = ['name', 'email'];
}
