<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Integration\Transition\Controller;

use FreshAdvance\Invoice\DataType\InvoiceConfigurationInterface;
use FreshAdvance\Invoice\DataType\InvoiceDataInterface;
use FreshAdvance\Invoice\Document\InvoiceGeneratorInterface;
use FreshAdvance\Invoice\Exception\AccessDenied;
use FreshAdvance\Invoice\Service\Invoice;
use FreshAdvance\Invoice\Service\InvoiceServiceInterface;
use FreshAdvance\Invoice\Transition\Controller\InvoiceController;
use FreshAdvance\Invoice\Transition\Core\RequestInterface;
use FreshAdvance\Invoice\Transition\Core\RequestProxy;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Application\Model\User;
use PHPUnit\Framework\TestCase;

/**
 * @covers InvoiceController
 */
class InvoiceControllerTest extends TestCase
{
    /**
     * @dataProvider accessTestDataProvider
     */
    public function testDownloadOrderInvoiceAsAdminAccess(bool $isAdmin, $user, bool $hasAccess): void
    {
        $userStub = $this->createConfiguredMock(User::class, ['getId' => 'someUserId']);
        $orderStub = $this->createConfiguredMock(Order::class, ['getOrderUser' => $userStub]);
        $invoiceDataStub = $this->createConfiguredMock(InvoiceDataInterface::class, ['getOrder' => $orderStub]);

        $invoiceDataServiceStub = $this->createConfiguredMock(Invoice::class, [
            'getInvoiceDataByOrderId' => $invoiceDataStub
        ]);

        $sut = $this->createPartialMock(
            InvoiceController::class,
            ['getUser', 'isAdmin', 'getServiceFromContainer', 'proceedToGenerateAndDownload', 'getOrderIdFromRequest']
        );
        $sut->method('getUser')->willReturn($user);
        $sut->method('isAdmin')->willReturn($isAdmin);
        $sut->method('getOrderIdFromRequest')->willReturn('someOrderId');
        $sut->method('getServiceFromContainer')->willReturnMap([
            [Invoice::class, $invoiceDataServiceStub],
        ]);

        if (!$hasAccess) {
            $this->expectException(AccessDenied::class);
        } else {
            $sut->expects($this->once())->method('proceedToGenerateAndDownload');
        }

        $sut->downloadOrderInvoice();
    }

    public function accessTestDataProvider(): array
    {
        return [
            [
                'isAdmin' => true,
                'user' => false,
                'hasAccess' => true
            ],
            [
                'isAdmin' => false,
                'user' => false,
                'hasAccess' => false
            ],
            [
                'isAdmin' => false,
                'user' => $this->createConfiguredMock(User::class, ['getId' => 'someUserId']),
                'hasAccess' => true
            ],
            [
                'isAdmin' => false,
                'user' => $this->createConfiguredMock(User::class, ['getId' => 'otherUserId']),
                'hasAccess' => false
            ],
        ];
    }

    public function testRequestParameterProcess(): void
    {
        $orderId = 'someOrderId';

        $requestStub = $this->createPartialMock(RequestProxy::class, ['getRequestEscapedParameter']);
        $requestStub->method('getRequestEscapedParameter')
            ->with(InvoiceController::ORDER_ID_REQUEST_PARAM)
            ->willReturn($orderId);

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

        $invoiceGeneratorMock = $this->createPartialMock(InvoiceGeneratorInterface::class, ['generate']);
        $invoiceGeneratorMock->expects($this->once())->method('generate')->with($invoiceDataStub);

        $invoiceServiceMock = $this->createPartialMock(InvoiceServiceInterface::class, ['triggerInvoiceFileDownload']);
        $invoiceServiceMock->expects($this->once())
            ->method('triggerInvoiceFileDownload')
            ->with("headerFilename.pdf", 'filepath.pdf');

        $sut = $this->createPartialMock(
            InvoiceController::class,
            ['checkActiveUserAccessRights', 'getServiceFromContainer']
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
