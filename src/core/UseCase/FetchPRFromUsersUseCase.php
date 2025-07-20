<?php

namespace App\Core\UseCase;

use App\Core\Repository\PRRepositoryInterface;

class FetchPRFromUsersUseCase extends UseCaseBase {

    public function __construct(
        private readonly PRRepositoryInterface $repository
    ) { }

    public function execute($payload): mixed {
        if (!isset($payload['movement_id']) && !isset($payload['movement_name'])) {
            throw new \InvalidArgumentException('Movement ID or Name is required');
        }

        $data = $this->repository->fetchPRFromUsers($payload);

        return $data;
    }

}