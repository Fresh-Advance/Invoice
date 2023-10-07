<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Integration\Transition\Controller\Admin;

use FreshAdvance\Invoice\DataType\InvoiceConfigurationInterface;
use FreshAdvance\Invoice\DataType\InvoiceDataInterface;
use FreshAdvance\Invoice\Document\InvoiceGeneratorInterface;
use FreshAdvance\Invoice\Service\Invoice;
use FreshAdvance\Invoice\Service\InvoiceServiceInterface;
use FreshAdvance\Invoice\Transition\Controller\Admin\InvoiceController;
use FreshAdvance\Invoice\Transput\RequestInterface;
use FreshAdvance\Invoice\Transput\RequestProxy;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FreshAdvance\Invoice\Transition\Controller\Admin\InvoiceController
 */
class InvoiceControllerTest extends TestCase
{
    public function testRender(): void
    {
        $invoiceDataStub = $this->createStub(InvoiceDataInterface::class);
        $invoiceServiceMock = $this->createPartialMock(Invoice::class, ['getInvoiceDataByOrderId']);
        $invoiceServiceMock->method('getInvoiceDataByOrderId')->willReturnMap([
            ['someOxid', $invoiceDataStub]
        ]);

        $sut = $this->createPartialMock(
            InvoiceController::class,
            ['getServiceFromContainer', 'getEditObjectId']
        );
        $sut->method('getServiceFromContainer')->willReturnMap([[Invoice::class, $invoiceServiceMock]]);
        $sut->method('getEditObjectId')->willReturn('someOxid');

        $this->assertStringStartsWith('@fa_invoice/admin/', $sut->render());

        $viewData = $sut->getViewData();
        $this->assertSame($invoiceDataStub, $viewData['invoiceData']);
    }

    public function testRequestParameterProcess(): void
    {
        $orderId = 'someOrderId';

        $requestStub = $this->createMock(RequestInterface::class);
        $requestStub->method('getInvoiceIdFromRequest')->willReturn($orderId);

        $invoiceConfigurationStub = $this->createStub(InvoiceConfigurationInterface::class);
        $invoiceDataStub = $this->createConfiguredMock(InvoiceDataInterface::class, [
            'getInvoicePath' => 'filepath.pdf',
            'getInvoiceConfiguration' => $invoiceConfigurationStub
        ]);

        $invoiceDataServiceStub = $this->createPartialMock(
            Invoice::class,
            ['getInvoiceDataByOrderId', 'getInvoiceFileName']
        );
        $invoiceDataServiceStub->method('getInvoiceDataByOrderId')
            ->with($orderId)
            ->willReturn($invoiceDataStub);
        $invoiceDataServiceStub->method('getInvoiceFileName')
            ->with($invoiceConfigurationStub)
            ->willReturn("headerFilename.pdf");

        $invoiceGeneratorMock = $this->createMock(InvoiceGeneratorInterface::class);
        $invoiceGeneratorMock->expects($this->once())->method('generate')->with($invoiceDataStub);

        $invoiceServiceMock = $this->createMock(InvoiceServiceInterface::class);
        $invoiceServiceMock->expects($this->once())
            ->method('triggerInvoiceFileDownload')
            ->with("headerFilename.pdf", 'filepath.pdf');

        $sut = $this->createPartialMock(
            InvoiceController::class,
            ['getServiceFromContainer']
        );
        $sut->method('getServiceFromContainer')->willReturnMap([
            [RequestInterface::class, $requestStub],
            [Invoice::class, $invoiceDataServiceStub],
            [InvoiceGeneratorInterface::class, $invoiceGeneratorMock],
            [InvoiceServiceInterface::class, $invoiceServiceMock],
        ]);

        $sut->downloadOrderInvoice();
    }
}
