<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\ElectronicInvoice\ZUGFeRD\BuilderConfigurator;

use FreshAdvance\Invoice\DataType\InvoiceDataInterface;
use FreshAdvance\Invoice\ElectronicInvoice\ZUGFeRD\BuilderConfigurator\BuilderTotalsConfigurator;
use horstoeko\zugferd\ZugferdDocumentBuilder;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Core\Price;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class BuilderTotalsConfiguratorTest extends TestCase
{
    #[Test]
    public function configuresDocumentTotalsWithOrderDetails(): void
    {
        $orderStub = $this->createMock(Order::class);
        $orderStub->method('getFieldData')
            ->willReturnMap([
                ['OXTOTALORDERSUM', $totalSum = rand(100, 1000)],
                ['OXTOTALNETSUM', $itemsNetSum = rand(100, 1000)],
                ['OXTOTALBRUTSUM', $itemsBrutSum = $itemsNetSum + rand(10, 20)],
                ['OXDISCOUNT', $discount = rand(10, 20)],
                ['OXVOUCHERDISCOUNT', $voucher = rand(10, 20)],
            ]);

        $orderStub->method('getOrderDeliveryPrice')
            ->willReturn(
                $this->createConfiguredStub(Price::class, [
                    'getNettoPrice' => $delNet = rand(10, 20),
                    'getVatValue' => $delVatVal = rand(1, 5),
                    'getBruttoPrice' => $delBrut = $delNet + $delVatVal,
                ])
            );
        $orderStub->method('getOrderPaymentPrice')
            ->willReturn(
                $this->createConfiguredStub(Price::class, [
                    'getNettoPrice' => $payNet = rand(10, 20),
                    'getVatValue' => $payVatVal = rand(1, 5),
                    'getBruttoPrice' => $payBrut = $payNet + $payVatVal,
                ])
            );
        $orderStub->method('getOrderWrappingPrice')
            ->willReturn(
                $this->createConfiguredStub(Price::class, [
                    'getNettoPrice' => $wrapNet = rand(10, 20),
                    'getVatValue' => $wrapVatVal = rand(1, 5),
                    'getBruttoPrice' => $wrapBrut = $wrapNet + $wrapVatVal,
                ])
            );

        $invoiceDataStub = $this->createConfiguredStub(InvoiceDataInterface::class, [
            'getOrder' => $orderStub,
        ]);

        $builderSpy = $this->createMock(ZugferdDocumentBuilder::class);
        $builderSpy->expects($this->once())
            ->method('setDocumentSummation')
            ->with(
                (float)$totalSum,
                (float)$totalSum,
                (float)$itemsNetSum,
                (float)($delBrut + $payBrut + $wrapBrut),
                (float)($discount + $voucher),
                (float)($itemsNetSum + $delNet + $payNet + $wrapNet),
                (float)($itemsBrutSum - $itemsNetSum + $delVatVal + $payVatVal + $wrapVatVal),
            )
            ->willReturn($builderSpy);

        $sut = new BuilderTotalsConfigurator();
        $result = $sut->configureBuilder($builderSpy, $invoiceDataStub);
        $this->assertSame($builderSpy, $result);
    }
}
