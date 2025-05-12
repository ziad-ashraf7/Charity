<?php

namespace App\models;

use Core\Database;

class BaseModel
{
    protected $db;
    protected $table;

    public function __construct($config = null)
    {
        $config = $config ?? require basePath('config/_db.php');
        $this->db = Database::getInstance($config)->getConnection();
    }
}