<?php 

namespace App\Presentation\Controller;

use App\Application\Request;
use App\Core\UseCase\FetchPRFromUsersUseCase;

class PersonalRecordController {
    
    public function __construct(
        private readonly FetchPRFromUsersUseCase $fetchPRFromUsersUseCase,
    ) {}

    public function fetchPRFromUsers(Request $request): array {
        return $this->fetchPRFromUsersUseCase->execute([
            'movement_id' => $request->query['movement_id'] ?? null,
            'movement_name' => $request->query['movement_name'] ?? null,
        ]);
    }
}