<?php 

namespace App\Presentation\Controller;

use App\Core\Repository\PRRepositoryInterface;
use App\Core\UseCase\FetchPRFromUsersUseCase;

class PersonalRecordController {
    
    public function __construct(
        private readonly FetchPRFromUsersUseCase $fetchPRFromUsersUseCase,
    ) {}

    public function fetchPRFromUsers(): array {
        return $this->fetchPRFromUsersUseCase->execute([]);
    }
}