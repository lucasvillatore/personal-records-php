<?php

namespace App\Infra\Database\MySQL;

use App\Application\Database;
use App\Core\Repository\PRRepositoryInterface;

class PRRepository implements PRRepositoryInterface {

    public function __construct(private readonly Database $database) {}
    public function fetchPRFromUsers(): array {

        $prs = $this
            ->database
            ->getConnection()
            ->query(`SELECT * FROM users`)
            ->fetchAll();

        return $prs;
    }
}