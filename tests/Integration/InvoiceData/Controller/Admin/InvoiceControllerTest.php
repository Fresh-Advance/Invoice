<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Integration\InvoiceData\Controller\Admin;

use FreshAdvance\Invoice\InvoiceData\Controller\Admin\InvoiceController;
use FreshAdvance\Invoice\InvoiceData\DataType\InvoiceDataInterface;
use FreshAdvance\Invoice\InvoiceData\InvoiceConfiguration\DataType\InvoiceConfigurationInterface;
use FreshAdvance\Invoice\InvoiceData\InvoiceConfiguration\Repository\InvoiceConfigurationRepositoryInterface;
use FreshAdvance\Invoice\InvoiceData\Service\InvoiceDataServiceInterface;
use FreshAdvance\Invoice\InvoiceData\Service\InvoiceFileServiceInterface;
use FreshAdvance\Invoice\InvoiceData\Settings\FormatSettingsInterface;
use FreshAdvance\Invoice\InvoiceData\Transput\RequestInterface;
use FreshAdvance\Invoice\Pdf\InvoiceGeneratorInterface;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(InvoiceController::class)]
class InvoiceControllerTest extends TestCase
{
    public function testRenderGivesMainVariablesToTemplate(): void
    {
        $invoiceDataStub = $this->createStub(InvoiceDataInterface::class);
        $invoiceServiceMock = $this->createPartialMock(InvoiceDataServiceInterface::class, ['getInvoiceDataByOrderId']);
        $invoiceServiceMock->method('getInvoiceDataByOrderId')->willReturnMap([
            ['someOxid', $invoiceDataStub]
        ]);

        $sut = $this->createPartialMock(InvoiceController::class, ['getService', 'getEditObjectId']);
        $sut->method('getService')->willReturnMap([
            [InvoiceDataServiceInterface::class, $invoiceServiceMock],
            [FormatSettingsInterface::class, $moduleSettingsStub = $this->createStub(FormatSettingsInterface::class)]
        ]);

        $sut->method('getEditObjectId')->willReturn('someOxid');

        $this->assertStringStartsWith('@fa_invoice/admin/', $sut->render());

        $viewData = $sut->getViewData();
        $this->assertSame($invoiceDataStub, $viewData['invoiceData']);
        $this->assertSame($moduleSettingsStub, $viewData['moduleSettings']);
    }

    public function testRenderSetsExistingFileData(): void
    {
        $tempDirectory = vfsStream::setup('root', null, [
            'filename.pdf' => 'someFileContent'
        ]);

        $invoiceDataStub = $this->createConfiguredMock(InvoiceDataInterface::class, [
            'getInvoicePath' => $tempDirectory->url() . '/filename.pdf',
            'getInvoiceConfiguration' => $this->createStub(InvoiceConfigurationInterface::class),

        ]);
        $invoiceDataServiceMock = $this->createMock(InvoiceDataServiceInterface::class);
        $invoiceDataServiceMock->method('getInvoiceDataByOrderId')->willReturnMap([
            ['someOxid', $invoiceDataStub]
        ]);

        $invoiceFileServiceMock = $this->createMock(InvoiceFileServiceInterface::class);
        $invoiceFileServiceMock->method('getInvoiceFileName')
            ->with($invoiceDataStub)
            ->willReturn($fileName = uniqid());

        $sut = $this->createPartialMock(
            InvoiceController::class,
            ['getService', 'getEditObjectId']
        );
        $sut->method('getService')->willReturnMap([
            [InvoiceDataServiceInterface::class, $invoiceDataServiceMock],
            [InvoiceFileServiceInterface::class, $invoiceFileServiceMock],
            [FormatSettingsInterface::class, $this->createStub(FormatSettingsInterface::class)],
        ]);
        $sut->method('getEditObjectId')->willReturn('someOxid');

        $sut->render();

        $viewData = $sut->getViewData();
        $this->assertTrue($viewData['invoiceExists']);
        $this->assertSame($fileName, $viewData['invoiceFileName']);
        $this->assertNotEmpty($viewData['invoiceDate']);
    }

    public function testSaveDataTriggersDataSave(): void
    {
        $invoiceConfigurationStub = $this->createStub(InvoiceConfigurationInterface::class);
        $requestStub = $this->createConfiguredMock(RequestInterface::class, [
            'getInvoiceIdFromRequest' => $invoiceId = uniqid(),
            'getInvoiceConfigurationFromRequest' => $invoiceConfigurationStub
        ]);

        $invoiceConfigurationRepositorySpy = $this->createMock(InvoiceConfigurationRepositoryInterface::class);
        $invoiceConfigurationRepositorySpy->expects($this->once())
            ->method('save')
            ->with($invoiceConfigurationStub);

        $invoiceDataStub = $this->createStub(InvoiceDataInterface::class);
        $invoiceServiceSpy = $this->createMock(InvoiceDataServiceInterface::class);
        $invoiceServiceSpy->method('getInvoiceDataByOrderId')
            ->with($invoiceId)
            ->willReturn($invoiceDataStub);

        $invoiceGeneratorSpy = $this->createMock(InvoiceGeneratorInterface::class);
        $invoiceGeneratorSpy->expects($this->once())
            ->method('generate')
            ->with($invoiceDataStub);

        $sut = $this->createPartialMock(
            InvoiceController::class,
            ['getService']
        );
        $sut->method('getService')->willReturnMap([
            [InvoiceDataServiceInterface::class, $invoiceServiceSpy],
            [InvoiceConfigurationRepositoryInterface::class, $invoiceConfigurationRepositorySpy],
            [RequestInterface::class, $requestStub],
            [InvoiceGeneratorInterface::class, $invoiceGeneratorSpy],
        ]);

        $sut->saveData();
    }

    public function testDownloadOrderInvoiceTriggersDownloadServiceWithCorrectParameters(): void
    {
        $requestStub = $this->createConfiguredMock(RequestInterface::class, [
            'getInvoiceIdFromRequest' => $invoiceId = uniqid(),
        ]);

        $invoiceDataStub = $this->createConfiguredMock(InvoiceDataInterface::class, [
            'getInvoicePath' => $invoicePath = uniqid(),
            'getInvoiceConfiguration' => $this->createStub(InvoiceConfigurationInterface::class)
        ]);

        $invoiceDataServiceMock = $this->createMock(InvoiceDataServiceInterface::class);
        $invoiceDataServiceMock->method('getInvoiceDataByOrderId')->with($invoiceId)->willReturn($invoiceDataStub);

        $invoiceServiceSpy = $this->createMock(InvoiceFileServiceInterface::class);
        $invoiceServiceSpy->method('getInvoiceFileName')
            ->with($invoiceDataStub)
            ->willReturn($invoiceFileName = uniqid());
        $invoiceServiceSpy->expects($this->once())
            ->method('triggerInvoiceFileDownload')
            ->with($invoiceFileName, $invoicePath);

        $sut = $this->createPartialMock(
            InvoiceController::class,
            ['getService']
        );
        $sut->method('getService')->willReturnMap([
            [InvoiceDataServiceInterface::class, $invoiceDataServiceMock],
            [RequestInterface::class, $requestStub],
            [InvoiceFileServiceInterface::class, $invoiceServiceSpy],
        ]);

        $sut->downloadOrderInvoice();
    }
}
