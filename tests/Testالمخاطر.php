<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\RiskController;
use App\Repository\RiskRepository;
use App\Entity\Risk;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use PHPUnit\Framework\MockObject\MockObject;

class Testالمخاطر extends TestCase
{
    private $riskController;
    private $riskRepository;
    private $entityManager;
    private $router;

    protected function setUp(): void
    {
        $this->riskRepository = $this->createMock(RiskRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->riskController = new RiskController($this->riskRepository, $this->entityManager, $this->router);
    }

    public function testGetAllRisks()
    {
        $risks = [
            new Risk(),
            new Risk(),
        ];
        $this->riskRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($risks);
        $response = $this->riskController->getAllRisks();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($risks), $response->getContent());
    }

    public function testGetRiskById()
    {
        $risk = new Risk();
        $risk->setId(1);
        $this->riskRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($risk);
        $response = $this->riskController->getRiskById(1);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($risk), $response->getContent());
    }

    public function testGetRiskByIdNotFound()
    {
        $this->riskRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);
        $response = $this->riskController->getRiskById(1);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function testCreateRisk()
    {
        $risk = new Risk();
        $risk->setName('Test Risk');
        $this->riskRepository->expects($this->once())
            ->method('save')
            ->with($risk);
        $response = $this->riskController->createRisk($risk);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals(json_encode($risk), $response->getContent());
    }

    public function testUpdateRisk()
    {
        $risk = new Risk();
        $risk->setId(1);
        $risk->setName('Test Risk');
        $this->riskRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($risk);
        $this->riskRepository->expects($this->once())
            ->method('save')
            ->with($risk);
        $response = $this->riskController->updateRisk(1, $risk);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($risk), $response->getContent());
    }

    public function testUpdateRiskNotFound()
    {
        $risk = new Risk();
        $risk->setId(1);
        $risk->setName('Test Risk');
        $this->riskRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);
        $response = $this->riskController->updateRisk(1, $risk);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function testDeleteRisk()
    {
        $risk = new Risk();
        $risk->setId(1);
        $this->riskRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($risk);
        $this->riskRepository->expects($this->once())
            ->method('remove')
            ->with($risk);
        $response = $this->riskController->deleteRisk(1);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteRiskNotFound()
    {
        $risk = new Risk();
        $risk->setId(1);
        $this->riskRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);
        $response = $this->riskController->deleteRisk(1);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }
}