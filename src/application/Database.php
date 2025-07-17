<?php

namespace App\Application;

use PDO;

class Database {

    private $instance = null;

    public function __construct(
        private readonly string $host,
        private readonly string $db, 
        private readonly string $user, 
        private readonly string $pass
        ) {
    } 

    public function connect(): void {
        if ($this->instance !== null) {
            return; 
        }
        
        $dsn = "mysql:host={$this->host};dbname={$this->db};charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->instance = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (\PDOException $e) {
            throw new \RuntimeException("Database connection failed: " . $e->getMessage());
        }
    }

    public function getConnection(): PDO {
        if ($this->instance === null) {
            $this->connect();
        }
        return $this->instance;
    }
}