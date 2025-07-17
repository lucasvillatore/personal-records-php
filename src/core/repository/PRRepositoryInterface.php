<?php

namespace App\Core\Repository;

interface PRRepositoryInterface {

    public function fetchPRFromUsers(): array;
}