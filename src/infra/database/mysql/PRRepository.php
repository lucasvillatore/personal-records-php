<?php

namespace App\Infra\Database\MySQL;

use App\Core\Repository\PRRepositoryInterface;

class PRRepository implements PRRepositoryInterface {

    public function fetchPRFromUsers(): array {
        return [
            [ 'name' => 'lucas', 'PR' => 100]
        ];
    }
}