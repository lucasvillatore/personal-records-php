<?php

use PHPUNit\Framework\TestCase;

class FetchPRFromUsersUseCaseTest extends TestCase
{

    private \App\Core\Repository\PRRepositoryInterface $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->createMock(\App\Core\Repository\PRRepositoryInterface::class);
        $this->repository->method('fetchPRFromUsers')
            ->willReturn([
                ['ranking' => 1, 'person_name' => 'John Doe', 'movement_name' => 'Bench Press', 'value' => 100, 'date' => '2023-01-01'],
                ['ranking' => 2, 'person_name' => 'Jane Smith', 'movement_name' => 'Bench Press', 'value' => 90, 'date' => '2023-01-02'],
            ]);

    }

    public function testShouldReturnAListOfPersonalRecordsOrderedDesc(): void
    {
        
        $useCase = new \App\Core\UseCase\FetchPRFromUsersUseCase($this->repository);

        $result = $useCase->execute(['movement_id' => 1, 'movement_name' => null]);

        $this->assertCount(2, $result);
        $this->assertEquals(expected: 'John Doe', actual: $result[0]['person_name']);
        $this->assertEquals(expected: 'Bench Press', actual: $result[0]['movement_name']);
        $this->assertEquals(expected: 100, actual: $result[0]['value']);
        $this->assertEquals(expected: '2023-01-01', actual: $result[0]['date']);
        $this->assertEquals(expected: 'Jane Smith', actual: $result[1]['person_name']);
        $this->assertEquals(expected: 'Bench Press', actual: $result[1]['movement_name']);
        $this->assertEquals(expected: 90, actual: $result[1]['value']);
        $this->assertEquals(expected: '2023-01-02', actual: $result[1]['date']);
    }

    public function testShouldThrowExceptionIfNoMovementIdOrNameProvided(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Movement ID or Name is required');

        $repository = $this->createMock(\App\Core\Repository\PRRepositoryInterface::class);
        $useCase = new \App\Core\UseCase\FetchPRFromUsersUseCase($repository);

        $useCase->execute([]);
    }
}