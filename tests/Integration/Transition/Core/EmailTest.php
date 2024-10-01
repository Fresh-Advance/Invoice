<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Integration\Transition\Core;

use FreshAdvance\Invoice\DataType\InvoiceDataInterface;
use FreshAdvance\Invoice\Document\InvoiceGeneratorInterface;
use FreshAdvance\Invoice\Service\Invoice;
use FreshAdvance\Invoice\Settings\ModuleSettingsInterface;
use FreshAdvance\Invoice\Transition\Core\Email;
use OxidEsales\Eshop\Application\Model\Order as OrderModel;
use OxidEsales\EshopCommunity\Tests\Integration\IntegrationTestCase;

/** @covers \FreshAdvance\Invoice\Transition\Core\Email */
class EmailTest extends IntegrationTestCase
{
    public function testInvoiceGeneratedAndAttachedWithOptionOn(): void
    {
        $sut = $this->createPartialMock(
            Email::class,
            ['faCallParentSend', 'faCallParentSendOrderEmailToUser', 'getServiceFromContainer', 'addAttachment']
        );

        $sut->method('getServiceFromContainer')->willReturnMap(
            $this->getDIConfiguration(
                invoiceDataService: $invoiceDataService = $this->createMock(Invoice::class),
                invoiceGenerator: $invoiceGeneratorSpy = $this->createMock(InvoiceGeneratorInterface::class),
                moduleSettings: $this->createConfiguredMock(ModuleSettingsInterface::class, [
                    'isSendInvoiceOnUserOrderEmailActive' => true,
                    'getInvoiceInOrderEmailFilename' => $fileName = uniqid()
                ]),
            )
        );

        $order = $this->createConfiguredMock(OrderModel::class, ['getId' => $orderId = uniqid()]);
        $invoiceDataService->method('getInvoiceDataByOrderId')
            ->with($orderId)
            ->willReturn(
                $invoiceDataStub = $this->createConfiguredMock(InvoiceDataInterface::class, [
                    'getInvoicePath' => $invoicePath = uniqid()
                ])
            );

        $invoiceGeneratorSpy->expects($this->once())->method('generate')->with($invoiceDataStub);
        $sut->method('faCallParentSendOrderEmailToUser')
            ->with($order, $emailSubject = uniqid())
            ->willReturn($parentOrderEmailSendResult = uniqid());
        $this->assertSame($parentOrderEmailSendResult, $sut->sendOrderEmailToUser($order, $emailSubject));

        $sut->expects($this->once())->method('addAttachment')
            ->with($invoicePath, $fileName);
        $sut->method('faCallParentSend')->willReturn($parentSendResult = uniqid());

        $this->assertSame($parentSendResult, $sut->send());

        // check second send will not trigger the attachment again
        $sut->send();
    }

    public function testInvoiceNotGeneratedAndNotAttachedWithOptionOff(): void
    {
        $sut = $this->createPartialMock(
            Email::class,
            ['faCallParentSend', 'faCallParentSendOrderEmailToUser', 'getServiceFromContainer', 'addAttachment']
        );
        $sut->method('getServiceFromContainer')->willReturnMap(
            $this->getDIConfiguration(
                invoiceGenerator: $invoiceGeneratorSpy = $this->createMock(InvoiceGeneratorInterface::class),
                moduleSettings: $this->createConfiguredMock(ModuleSettingsInterface::class, [
                    'isSendInvoiceOnUserOrderEmailActive' => false
                ])
            )
        );

        $invoiceGeneratorSpy->expects($this->never())->method('generate');

        $order = $this->createConfiguredMock(OrderModel::class, ['getId' => uniqid()]);

        $sut->method('faCallParentSendOrderEmailToUser')
            ->with($order, $emailSubject = uniqid())
            ->willReturn($parentOrderEmailSendResult = uniqid());
        $this->assertSame($parentOrderEmailSendResult, $sut->sendOrderEmailToUser($order, $emailSubject));

        $sut->method('faCallParentSend')->willReturn($parentSendResult = uniqid());
        $sut->expects($this->never())->method('addAttachment');
        $this->assertSame($parentSendResult, $sut->send());
    }

    protected function getDIConfiguration(
        Invoice $invoiceDataService = null,
        InvoiceGeneratorInterface $invoiceGenerator = null,
        ModuleSettingsInterface $moduleSettings = null,
    ): array {
        return [
            [
                Invoice::class,
                $invoiceDataService ?? $this->createStub(Invoice::class)
            ],
            [
                InvoiceGeneratorInterface::class,
                $invoiceGenerator ?? $this->createStub(InvoiceGeneratorInterface::class)
            ],
            [
                ModuleSettingsInterface::class,
                $moduleSettings ?? $this->createStub(ModuleSettingsInterface::class)
            ],
        ];
    }
}
