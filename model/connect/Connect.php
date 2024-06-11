<?php

namespace Connect;

use PDO;
use PDOException;

class Connect
{
    public $conn;

    public function __construct()
    {
        $this->conn = null;
    }
    public function connect()
    {
        // Assuming connection settings are externalized in a config file or environment variables
        $host = 'congnghemoi.cxoe844yuzru.ap-southeast-2.rds.amazonaws.com';  // Host name
        $dbname = 'congnghemoi';
        $username = 'admin';  // Database username
        $password = 'iuh20113401';  // Database password
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

        try {
            return new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            throw new \Exception("Database connection error: " . $e->getMessage());
        }
    }
}
