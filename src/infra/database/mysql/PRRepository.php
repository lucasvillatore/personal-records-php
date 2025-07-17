<?php

namespace App\Infra\Database\MySQL;

use App\Application\MySQLPDO;
use App\Core\Repository\PRRepositoryInterface;
use PDO;

class PRRepository implements PRRepositoryInterface {

    public function __construct(private readonly MySQLPDO $database) {}

    public function fetchPRFromUsers(): array {

        $stmt = $this
            ->database
            ->getConnection()
            ->prepare("
                SELECT u.name as nome_pessoa, m.name as nome_movimento, pr.value, pr.date FROM personal_record pr
                INNER JOIN user u ON u.id = pr.user_id
                INNER JOIN movement m ON m.id = pr.movement_id
                ORDER BY
                    pr.value desc
            ");

        $stmt->execute();

        $prs = $stmt->fetchAll();

        return $prs;
    }
}