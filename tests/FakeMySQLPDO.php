<?php
namespace Tests;

use App\Application\MySQLPDO;
use PDO;

class FakeMySQLPDO extends MySQLPDO
{
    private ?PDO $pdo = null;

    public function __construct()
    {
    }

    public function connect(): void
    {
        if ($this->pdo === null) {
            $this->pdo = new PDO('sqlite::memory:');
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
    }

    public function getConnection(): PDO
    {
        $this->connect();
        return $this->pdo;
    }
}
