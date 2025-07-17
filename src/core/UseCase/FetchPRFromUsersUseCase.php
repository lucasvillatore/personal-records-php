<?php

namespace App\Core\UseCase;

use App\Core\Repository\PRRepositoryInterface;

class FetchPRFromUsersUseCase extends UseCaseBase {

    public function __construct(
        private readonly PRRepositoryInterface $repository
    ) { }

    public function execute($payloa): mixed {
        $data = $this->repository->fetchPRFromUsers();

        return [
            [
                "name" => "lucas",
                "pr" => "10 milhao de kg"
            ]
        ];
    }

}