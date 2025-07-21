<?php

namespace App\Infra\Database\MySQL;

use App\Application\MySQLPDO;
use App\Core\Repository\PRRepositoryInterface;

class PRRepository implements PRRepositoryInterface {

    public function __construct(private readonly MySQLPDO $database) {}

    public function fetchPRFromUsers(array $params): array
    {
        $conditions = [];
        $bindings = [];

        if ($params['movement_id'] !== null) {
            $conditions[] = 'm.id = :movement_id';
            $bindings['movement_id'] = $params['movement_id'];
        }

        if ($params['movement_name'] !== null) {
            $conditions[] = 'm.name = :movement_name';
            $bindings['movement_name'] = $params['movement_name'];
        }

        $whereClause = '';
        if (!empty($conditions)) {
            $whereClause = 'WHERE ' . implode(' AND ', $conditions);
        }

        $stmt = $this
            ->database
            ->getConnection()
            ->prepare("
           SELECT 
                RANK() OVER (ORDER BY pr.value DESC) AS ranking,
                u.name AS person_name,
                m.name AS movement_name,
                pr.value,
                pr.date
            FROM personal_record pr
            INNER JOIN user u ON u.id = pr.user_id
            INNER JOIN movement m ON m.id = pr.movement_id
            $whereClause
            ORDER BY ranking;
        ");

        $stmt->execute($bindings);

        return $stmt->fetchAll();
    }

}