<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\ElectronicInvoice\ZUGFeRD\BuilderConfigurator;

use FreshAdvance\Invoice\DataType\InvoiceDataInterface;
use FreshAdvance\Invoice\ElectronicInvoice\ZUGFeRD\BuilderConfigurator\BuilderConfiguratorInterface;
use FreshAdvance\Invoice\ElectronicInvoice\ZUGFeRD\BuilderConfigurator\BuilderVatConfigurator;
use horstoeko\zugferd\ZugferdDocumentBuilder;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Application\Model\OrderArticle;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class BuilderVatConfiguratorTest extends TestCase
{
    #[Test]
    public function allOrderVatsAreCalculatedFromOrderItemsAndRegistered(): void
    {
        $vat1 = rand(10, 20);
        $vat2 = rand(10, 20);

        $item1Stub = $this->createStub(OrderArticle::class);
        $item1Stub->method('getFieldData')
            ->willReturnMap([
                ['OXVAT', $vat1],
                ['OXNETPRICE', $item1net = rand(100, 200)],
                ['OXVATPRICE', $item1vat = rand(100, 200)],
            ]);

        $item2Stub = $this->createStub(OrderArticle::class);
        $item2Stub->method('getFieldData')
            ->willReturnMap([
                ['OXVAT', $vat1],
                ['OXNETPRICE', $item2net = rand(100, 200)],
                ['OXVATPRICE', $item2vat = rand(100, 200)],
            ]);

        $item3Stub = $this->createStub(OrderArticle::class);
        $item3Stub->method('getFieldData')
            ->willReturnMap([
                ['OXVAT', $vat2],
                ['OXNETPRICE', $item3net = rand(100, 200)],
                ['OXVATPRICE', $item3vat = rand(100, 200)],
            ]);

        $invoiceDataStub = $this->createConfiguredStub(InvoiceDataInterface::class, [
            'getOrder' => $this->createConfiguredMock(Order::class, [
                'getOrderArticles' => [
                    $item1Stub,
                    $item2Stub,
                    $item3Stub
                ]
            ]),
        ]);

        $expectedVats = [
            $vat1 => [
                'net' => $item1net + $item2net,
                'vat' => $item1vat + $item2vat,
            ],
            $vat2 => [
                'net' => $item3net,
                'vat' => $item3vat,
            ]
        ];

        $builderSpy = $this->createMock(ZugferdDocumentBuilder::class);

        $builderSpy->expects($this->exactly(2))
            ->method('addDocumentTax')
            ->willReturnCallback(function (
                string $type,
                string $name,
                float $netAmount,
                float $taxAmount,
                float $rate
            ) use (
                $expectedVats,
                $builderSpy
            ) {
                $this->assertSame("S", $type);
                $this->assertSame("VAT", $name);

                $this->assertSame((float)$expectedVats[$rate]['net'], $netAmount);
                $this->assertSame((float)$expectedVats[$rate]['vat'], $taxAmount);

                return $builderSpy;
            });

        $sut = $this->getSut();

        $result = $sut->configureBuilder($builderSpy, $invoiceDataStub);
        $this->assertSame($builderSpy, $result);
    }

    public function getSut(): BuilderConfiguratorInterface
    {
        return new BuilderVatConfigurator();
    }
}
