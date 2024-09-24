<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Integration\Transition\Core;

use FreshAdvance\Invoice\DataType\InvoiceDataInterface;
use FreshAdvance\Invoice\Transition\Core\Email;
use OxidEsales\EshopCommunity\Tests\Integration\IntegrationTestCase;

/** @covers \FreshAdvance\Invoice\Transition\Core\Email */
class EmailTest extends IntegrationTestCase
{
    public function testInvoiceGeneratedDuringSendOrderEmailToUserMethod(): void
    {
        $sut = $this->createPartialMock(
            Email::class,
            ['faCallParentSendOrderEmailToUser', 'getServiceFromContainer']
        );
        $sut->method('faCallParentSendOrderEmailToUser')->willReturn($parentOrderEmailSendResult = uniqid());
        $sut->method('getServiceFromContainer')->willReturnMap([
            [
                \FreshAdvance\Invoice\Service\Invoice::class,
                $invoiceDataService = $this->createMock(\FreshAdvance\Invoice\Service\Invoice::class)
            ],
            [
                \FreshAdvance\Invoice\Document\InvoiceGeneratorInterface::class,
                $generatorSpy = $this->createMock(\FreshAdvance\Invoice\Document\InvoiceGeneratorInterface::class)
            ],
        ]);

        $orderStub = $this->createConfiguredMock(\OxidEsales\Eshop\Application\Model\Order::class, [
            'getId' => $orderId = uniqid()
        ]);

        $invoiceDataService->method('getInvoiceDataByOrderId')
            ->with($orderId)
            ->willReturn(
                $invoiceData = $this->createMock(InvoiceDataInterface::class)
            );

        $generatorSpy->expects($this->once())->method('generate')->with($invoiceData);

        $orderEmailResult = $sut->sendOrderEmailToUser($orderStub, "some subject");

        $this->assertSame($parentOrderEmailSendResult, $orderEmailResult);
    }

    public function testInvoiceAttachedIfGeneratedDuringSendOrderEmailMethod(): void
    {
        $sut = $this->createPartialMock(
            Email::class,
            ['faCallParentSend', 'faCallParentSendOrderEmailToUser', 'getServiceFromContainer', 'addAttachment']
        );
        $sut->method('faCallParentSend')->willReturn($parentSendResult = uniqid());
        $sut->method('getServiceFromContainer')->willReturnMap([
            [
                \FreshAdvance\Invoice\Service\Invoice::class,
                $invoiceDataService = $this->createMock(\FreshAdvance\Invoice\Service\Invoice::class)
            ],
            [
                \FreshAdvance\Invoice\Document\InvoiceGeneratorInterface::class,
                $this->createMock(\FreshAdvance\Invoice\Document\InvoiceGeneratorInterface::class)
            ],
        ]);

        $order = $this->createConfiguredMock(\OxidEsales\Eshop\Application\Model\Order::class, [
            'getId' => $orderId = uniqid()
        ]);

        $invoiceDataService->method('getInvoiceDataByOrderId')
            ->with($orderId)
            ->willReturn(
                $this->createConfiguredMock(InvoiceDataInterface::class, [
                    'getInvoicePath' => $invoicePath = 'example.pdf'
                ])
            );

        $sut->sendOrderEmailToUser($order, "some subject");

        $sut->expects($this->once())->method('addAttachment')->with(
            $invoicePath,
            'example.pdf'
        );

        $sendResult = $sut->send();
        $this->assertSame($parentSendResult, $sendResult);

        // check second send will not trigger the attachment again
        $sut->send();
    }
}
