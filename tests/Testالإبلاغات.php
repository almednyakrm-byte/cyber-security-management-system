<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\ReportsController;
use App\Repository\ReportsRepository;
use App\Service\ReportsService;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Testالإبلاغات extends TestCase
{
    private $controller;
    private $repository;
    private $service;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock('PDO');
        $this->repository = $this->createMock(ReportsRepository::class);
        $this->service = $this->createMock(ReportsService::class);
        $this->controller = new ReportsController($this->repository, $this->service);
    }

    public function testGetReports()
    {
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                ['id' => 1, 'title' => 'Report 1'],
                ['id' => 2, 'title' => 'Report 2'],
            ]);

        $response = $this->controller->getReports();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testGetReportById()
    {
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(['id' => 1, 'title' => 'Report 1']);

        $response = $this->controller->getReport(1);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testGetReportByIdNotFound()
    {
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);
        $this->controller->getReport(1);
    }

    public function testCreateReport()
    {
        $data = ['title' => 'New Report'];
        $this->service->expects($this->once())
            ->method('create')
            ->with($data)
            ->willReturn(['id' => 1, 'title' => 'New Report']);

        $response = $this->controller->createReport(Request::create('/reports', 'POST', [], [], [], json_encode($data)));
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testUpdateReport()
    {
        $data = ['title' => 'Updated Report'];
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(['id' => 1, 'title' => 'Report 1']);

        $this->service->expects($this->once())
            ->method('update')
            ->with(1, $data)
            ->willReturn(['id' => 1, 'title' => 'Updated Report']);

        $response = $this->controller->updateReport(1, Request::create('/reports/1', 'PUT', [], [], [], json_encode($data)));
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testUpdateReportNotFound()
    {
        $data = ['title' => 'Updated Report'];
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);
        $this->controller->updateReport(1, Request::create('/reports/1', 'PUT', [], [], [], json_encode($data)));
    }

    public function testDeleteReport()
    {
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(['id' => 1, 'title' => 'Report 1']);

        $this->service->expects($this->once())
            ->method('delete')
            ->with(1);

        $response = $this->controller->deleteReport(1);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteReportNotFound()
    {
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);
        $this->controller->deleteReport(1);
    }
}


This test file covers the following scenarios:

1.  **Get Reports**: Tests the `getReports` method to ensure it returns a list of reports.
2.  **Get Report by ID**: Tests the `getReport` method to ensure it returns a report by its ID.
3.  **Get Report by ID (NotFound)**: Tests the `getReport` method to ensure it throws a `NotFoundHttpException` when the report is not found.
4.  **Create Report**: Tests the `createReport` method to ensure it creates a new report.
5.  **Update Report**: Tests the `updateReport` method to ensure it updates an existing report.
6.  **Update Report (NotFound)**: Tests the `updateReport` method to ensure it throws a `NotFoundHttpException` when the report is not found.
7.  **Delete Report**: Tests the `deleteReport` method to ensure it deletes a report.
8.  **Delete Report (NotFound)**: Tests the `deleteReport` method to ensure it throws a `NotFoundHttpException` when the report is not found.