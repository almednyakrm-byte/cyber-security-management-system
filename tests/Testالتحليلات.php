<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedTokenInterface;
use App\Controller\AnalysisController;
use App\Repository\AnalysisRepository;
use App\Entity\Analysis;
use App\Service\AnalysisService;
use PHPUnit\Framework\MockObject\MockObject;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\TransactionRequiredException;

class Testالتحليلات extends TestCase
{
    private $controller;
    private $router;
    private $tokenStorage;
    private $userProvider;
    private $entityManager;
    private $analysisRepository;
    private $analysisService;

    protected function setUp(): void
    {
        $this->router = $this->createMock(RouterInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->userProvider = $this->createMock(UserProviderInterface::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->analysisRepository = $this->createMock(AnalysisRepository::class);
        $this->analysisService = $this->createMock(AnalysisService::class);

        $this->controller = new AnalysisController(
            $this->router,
            $this->tokenStorage,
            $this->userProvider,
            $this->entityManager,
            $this->analysisRepository,
            $this->analysisService
        );
    }

    public function testGetAllAnalyses(): void
    {
        $analyses = [
            new Analysis(),
            new Analysis(),
        ];

        $this->entityManager->expects($this->once())
            ->method('getRepository')
            ->with('App\Entity\Analysis')
            ->willReturn($this->analysisRepository);

        $this->analysisRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($analyses);

        $response = $this->controller->getAllAnalyses(new Request());

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($analyses), $response->getContent());
    }

    public function testCreateAnalysis(): void
    {
        $analysis = new Analysis();
        $analysis->setId(1);

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($analysis);

        $this->entityManager->expects($this->once())
            ->method('flush')
            ->willReturn(null);

        $request = new Request();
        $request->request->set('name', 'Analysis Name');
        $request->request->set('description', 'Analysis Description');

        $response = $this->controller->createAnalysis($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals(json_encode($analysis), $response->getContent());
    }

    public function testUpdateAnalysis(): void
    {
        $analysis = new Analysis();
        $analysis->setId(1);

        $this->entityManager->expects($this->once())
            ->method('getRepository')
            ->with('App\Entity\Analysis')
            ->willReturn($this->analysisRepository);

        $this->analysisRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($analysis);

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($analysis);

        $this->entityManager->expects($this->once())
            ->method('flush')
            ->willReturn(null);

        $request = new Request();
        $request->request->set('name', 'Updated Analysis Name');
        $request->request->set('description', 'Updated Analysis Description');

        $response = $this->controller->updateAnalysis(1, $request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($analysis), $response->getContent());
    }

    public function testDeleteAnalysis(): void
    {
        $analysis = new Analysis();
        $analysis->setId(1);

        $this->entityManager->expects($this->once())
            ->method('getRepository')
            ->with('App\Entity\Analysis')
            ->willReturn($this->analysisRepository);

        $this->analysisRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($analysis);

        $this->entityManager->expects($this->once())
            ->method('remove')
            ->with($analysis);

        $this->entityManager->expects($this->once())
            ->method('flush')
            ->willReturn(null);

        $response = $this->controller->deleteAnalysis(1);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


This test file covers the CRUD operations for the 'التحليلات' module using mocked PDO statements. It includes tests for GET, POST, PUT, and DELETE requests. The tests are written using the PHPUnit framework.