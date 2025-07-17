<?php

namespace App\Infra\Database\MySQL;

use App\Application\MySQLPDO;
use App\Core\Repository\PRRepositoryInterface;

class PRRepository implements PRRepositoryInterface {

    public function __construct(private readonly MySQLPDO $database) {}
    public function fetchPRFromUsers(): array {

        $prs = $this
            ->database
            ->getConnection()
            ->query(`SELECT * FROM users`)
            ->fetchAll();

        return $prs;
    }
}