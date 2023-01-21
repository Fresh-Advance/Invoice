<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\Transition\Controller\Admin;

use FreshAdvance\Invoice\DataType\InvoiceDataInterface;
use FreshAdvance\Invoice\Service\Invoice;
use FreshAdvance\Invoice\Transition\Controller\Admin\InvoiceController;
use org\bovigo\vfs\vfsStream;
use OxidEsales\Eshop\Core\Utils;
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

    public function testDownloadOrderInvoice(): void
    {
        $tempDirectory = vfsStream::setup('root', null, [
            'filename.pdf' => 'someFileContent'
        ]);

        $invoiceDataStub = $this->createConfiguredMock(InvoiceDataInterface::class, [
            'getInvoicePath' => $tempDirectory->url() . '/filename.pdf'
        ]);
        $invoiceServiceMock = $this->createPartialMock(
            Invoice::class,
            ['getInvoiceDataByOrderId', 'getInvoiceFileName']
        );
        $invoiceServiceMock->method('getInvoiceDataByOrderId')->willReturnMap([
            ['someOxid', $invoiceDataStub]
        ]);
        $invoiceServiceMock->method('getInvoiceFileName')->willReturn('someFilename.pdf');

        $utilsMock = $this->createPartialMock(Utils::class, ['showMessageAndExit', 'setHeader']);
        $utilsMock->expects($this->atLeastOnce())->method('setHeader');
        $utilsMock->expects($this->atLeastOnce())->method('showMessageAndExit')->with('someFileContent');

        $sut = $this->createPartialMock(
            InvoiceController::class,
            ['getServiceFromContainer', 'getEditObjectId']
        );
        $sut->method('getServiceFromContainer')->willReturnMap([
            [Invoice::class, $invoiceServiceMock],
            [Utils::class, $utilsMock],
        ]);
        $sut->method('getEditObjectId')->willReturn('someOxid');

        $sut->downloadOrderInvoice();
    }
}
