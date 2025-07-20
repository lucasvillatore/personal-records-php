<?php
namespace Tests;

use App\Infra\Database\MySQL\PRRepository;
use PHPUnit\Framework\TestCase;
use Tests\FakeMySQLPDO;
use PDO;

class PRRepositoryTest extends TestCase
{
    private PRRepository $repo;
    private PDO $pdo;

    protected function setUp(): void
    {
        $fakeDb = new FakeMySQLPDO();
        $this->pdo = $fakeDb->getConnection();

        // Criar esquema simplificado para os testes
        $this->pdo->exec("
            CREATE TABLE user (
                id INTEGER PRIMARY KEY,
                name TEXT
            );
            CREATE TABLE movement (
                id INTEGER PRIMARY KEY,
                name TEXT
            );
            CREATE TABLE personal_record (
                id INTEGER PRIMARY KEY,
                user_id INTEGER,
                movement_id INTEGER,
                value FLOAT,
                date TEXT
            );
        ");

        $this->pdo->exec("
            INSERT INTO user (id, name) VALUES (1, 'Joao'), (2, 'Jose');
            INSERT INTO movement (id, name) VALUES (1, 'Deadlift'), (2, 'Bench Press');
            INSERT INTO personal_record (id, user_id, movement_id, value, date) VALUES
                (1, 1, 1, 100.0, '2021-01-01'),
                (2, 1, 1, 150.0, '2021-01-02'),
                (3, 2, 2, 120.0, '2021-01-01');
        ");

        $this->repo = new PRRepository($fakeDb);
    }

    public function testFetchPRFromUsersByMovementId(): void
    {
        $result = $this->repo->fetchPRFromUsers(['movement_id' => 1, 'movement_name' => null]);

        $this->assertCount(2, $result);

        $this->assertEquals('Joao', $result[0]['person_name']);
        $this->assertEquals('Deadlift', $result[0]['movement_name']);
        $this->assertEquals(150.0, $result[0]['value']);
    }

    public function testFetchPRFromUsersByMovementName(): void
    {
        $result = $this->repo->fetchPRFromUsers(['movement_id' => null, 'movement_name' => 'Bench Press']);

        $this->assertCount(1, $result);

        $this->assertEquals('Jose', $result[0]['person_name']);
        $this->assertEquals('Bench Press', $result[0]['movement_name']);
        $this->assertEquals(120.0, $result[0]['value']);
    }

    public function testFetchPRFromUsersNoFilter(): void
    {
        $result = $this->repo->fetchPRFromUsers(['movement_id' => null, 'movement_name' => null]);

        $this->assertCount(3, $result);
    }
}
