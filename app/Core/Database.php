<?php

namespace App\Core;

use PDO;
use PDOException;

class Database {

    /** @var PDO */
    public $database;

    /** @var PDOException */
    public $errors;

    /** @var self */
    private static $dbInstance;

    /**
     * Description: Set DB config params and make new connect to DB
     */
    private function __construct()
    {
        $dsn = DB_DRIVER . ':host=' . DB_HOST . ';dbname=' . DB_NAME . ';port=' . DB_PORT . ';charset=UTF8';
        try {
            $this->database = new PDO($dsn, DB_USERNAME, DB_PASSWORD, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            if (!$this->database) {
                throw new PDOException('Error connection to DB');
            }
        } catch(PDOException $ex) {
            $this->errors = $ex;
            echo $this->errors;
            exit;
        }
    }

    /**
     * Description: Singleton instance of DB object connect.
     */
    public static function connect(): self
    {
        if (null === self::$dbInstance) {
            self::$dbInstance = new self();
        }

        return self::$dbInstance;
    }

    /**
     * Description:
     */
    private function __clone() {} // prevent cloning

    /**
     * Description:
     */
    private function __wakeup() {} // prevent unserialization
}
