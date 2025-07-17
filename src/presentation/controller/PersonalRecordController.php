<?php 

namespace App\Presentation\Controller;

use App\Core\Repository\PRRepositoryInterface;

class PersonalRecordController {
    
    public function __construct(
        private readonly PRRepositoryInterface $repository,
    ) {}

    public function fetchPRFromUsers(): array {
        return $this->repository->fetchPRFromUsers();
    }
}