<?php

namespace App\Models;

use App\Core\Database;

/**
 * This is Description of Model class
 */
abstract class Model
{
    /** @var Database */
    protected $db;

    /**
     * Create new Model instance.
     */
    public function __construct()
    {
        $this->db = Database::connect()->database;
    }
}
