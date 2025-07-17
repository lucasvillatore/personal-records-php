<?php

namespace App\Core\UseCase;

use App\Core\Repository\PRRepositoryInterface;

class FetchPRFromUsersUseCase extends UseCaseBase {

    public function __construct(
        private readonly PRRepositoryInterface $repository
    ) { }

    public function execute($payload): mixed {
        $data = $this->repository->fetchPRFromUsers();

        return $data;
    }

}