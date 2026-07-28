<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class Testالتصحيحات extends TestCase
{
    private $pdo;
    private $stmt;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);
    }

    public function testGetالتصحيحات()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM التصحيحات')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'تصحيح 1'],
                ['id' => 2, 'name' => 'تصحيح 2'],
            ]);

        $result = $this->getالتصحيحات($this->pdo);
        $this->assertEquals([
            ['id' => 1, 'name' => 'تصحيح 1'],
            ['id' => 2, 'name' => 'تصحيح 2'],
        ], $result);
    }

    public function testPostالتصحيحات()
    {
        $data = ['name' => 'تصحيح جديد'];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO التصحيحات (name) VALUES (:name)')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':name', $data['name']);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->pdo->expects($this->once())
            ->method('lastInsertId')
            ->willReturn(3);

        $result = $this->postالتصحيحات($this->pdo, $data);
        $this->assertEquals(3, $result);
    }

    public function testPutالتصحيحات()
    {
        $id = 1;
        $data = ['name' => 'تصحيح محدث'];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE التصحيحات SET name = :name WHERE id = :id')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':name', $data['name']);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':id', $id);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $result = $this->putالتصحيحات($this->pdo, $id, $data);
        $this->assertTrue($result);
    }

    public function testDeleteالتصحيحات()
    {
        $id = 1;

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM التصحيحات WHERE id = :id')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('bindParam')
            ->with(':id', $id);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $result = $this->deleteالتصحيحات($this->pdo, $id);
        $this->assertTrue($result);
    }

    private function getالتصحيحات(PDO $pdo)
    {
        $stmt = $pdo->prepare('SELECT * FROM التصحيحات');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    private function postالتصحيحات(PDO $pdo, array $data)
    {
        $stmt = $pdo->prepare('INSERT INTO التصحيحات (name) VALUES (:name)');
        $stmt->bindParam(':name', $data['name']);
        $stmt->execute();
        return $pdo->lastInsertId();
    }

    private function putالتصحيحات(PDO $pdo, int $id, array $data)
    {
        $stmt = $pdo->prepare('UPDATE التصحيحات SET name = :name WHERE id = :id');
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    private function deleteالتصحيحات(PDO $pdo, int $id)
    {
        $stmt = $pdo->prepare('DELETE FROM التصحيحات WHERE id = :id');
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}