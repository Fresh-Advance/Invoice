<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\Order\Decoration;

use FreshAdvance\Invoice\DataType\InvoiceDataInterface;
use FreshAdvance\Invoice\Document\InvoiceGeneratorInterface;
use FreshAdvance\Invoice\Order\Decoration\InvoiceGeneratorDecorator;
use FreshAdvance\Invoice\Order\Service\OrderServiceInterface;
use FreshAdvance\Invoice\Order\Settings\OrderSettingsInterface;
use OxidEsales\Eshop\Application\Model\Order;
use PHPUnit\Framework\TestCase;

/** @covers \FreshAdvance\Invoice\Order\Decoration\InvoiceGeneratorDecorator */
class InvoiceGeneratorDecoratorTest extends TestCase
{
    public function testOriginalGeneratorCalledWithCorrectParameter(): void
    {
        $invoiceDataStub = $this->createStub(InvoiceDataInterface::class);

        $generatorSpy = $this->createMock(InvoiceGeneratorInterface::class);
        $generatorSpy->expects($this->once())
            ->method('generate')
            ->with($invoiceDataStub);

        $sut = $this->getSut($generatorSpy);

        $sut->generate($invoiceDataStub);
    }

    public function testGenerateTriggersOrderNumberingUpdate(): void
    {
        $invoiceData = $this->createConfiguredMock(InvoiceDataInterface::class, [
            'getOrder' => $this->createStub(Order::class)
        ]);

        $sut = $this->getSut(
            orderService: $orderServiceSpy = $this->createMock(OrderServiceInterface::class),
            orderSettings: $this->createConfiguredMock(OrderSettingsInterface::class, [
                'isOrderInvoiceNumberUpdateActive' => true,
            ]),
        );

        $orderServiceSpy->expects($this->once())
            ->method('prepareOrderInvoiceNumber')
            ->with($invoiceData);

        $sut->generate($invoiceData);
    }

    public function testNumberingUpdateIsNotTriggeredIfSettingTurnedOff(): void
    {
        $sut = $this->getSut(
            orderService: $orderServiceSpy = $this->createMock(OrderServiceInterface::class),
            orderSettings: $this->createConfiguredMock(OrderSettingsInterface::class, [
                'isOrderInvoiceNumberUpdateActive' => false,
            ]),
        );

        $orderServiceSpy->expects($this->never())
            ->method('prepareOrderInvoiceNumber');

        $sut->generate($this->createStub(InvoiceDataInterface::class));
    }

    public function getSut(
        InvoiceGeneratorInterface $originalGenerator = null,
        OrderServiceInterface $orderService = null,
        OrderSettingsInterface $orderSettings = null,
    ): InvoiceGeneratorInterface {
        return new InvoiceGeneratorDecorator(
            originalGenerator: $originalGenerator ?? $this->createStub(InvoiceGeneratorInterface::class),
            orderService: $orderService ?? $this->createStub(OrderServiceInterface::class),
            orderSettings: $orderSettings ?? $this->createStub(OrderSettingsInterface::class),
        );
    }
}
