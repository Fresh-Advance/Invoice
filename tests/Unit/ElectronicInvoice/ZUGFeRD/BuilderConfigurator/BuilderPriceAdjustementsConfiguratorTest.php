<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\ElectronicInvoice\ZUGFeRD\BuilderConfigurator;

use FreshAdvance\Invoice\DataType\InvoiceDataInterface;
use FreshAdvance\Invoice\ElectronicInvoice\ZUGFeRD\BuilderConfigurator\BuilderPriceAdjustementsConfigurator;
use Generator;
use horstoeko\zugferd\ZugferdDocumentBuilder;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Core\Price;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class BuilderPriceAdjustementsConfiguratorTest extends TestCase
{
    public static function zeroAndNotZeroVatCasesDataProvider(): Generator
    {
        yield 'zero case' => [
            'vat' => 0,
            'expectedCode' => 'Z',
        ];

        yield 'not zero case' => [
            'vat' => rand(1, 10),
            'expectedCode' => 'S',
        ];
    }

    #[Test]
    #[DataProvider('zeroAndNotZeroVatCasesDataProvider')]
    public function configuresDeliveryPriceAdjustement(float $vat, string $expectedCode): void
    {
        $orderStub = $this->createMock(Order::class);

        $orderStub->method('getOrderDeliveryPrice')
            ->willReturn(
                $this->createConfiguredStub(Price::class, [
                    'getNettoPrice' => $delNet = rand(10, 20),
                    'getVat' => $vat,
                ])
            );

        $orderStub->method('getOrderPaymentPrice')->willReturn($this->createStub(Price::class));
        $orderStub->method('getOrderWrappingPrice')->willReturn($this->createStub(Price::class));

        $invoiceDataStub = $this->createConfiguredStub(InvoiceDataInterface::class, [
            'getOrder' => $orderStub,
        ]);

        $builderSpy = $this->createMock(ZugferdDocumentBuilder::class);

        $expectedCallsStack = [
            [
                'actualAmount' => (float)$delNet,
                'isCharge' => true,
                'taxCategoryCode' => $expectedCode,
                'taxTypeCode' => 'VAT',
                'rateApplicablePercent' => $vat,
                ...array_fill(0, 6, null),
                'reason' => 'Delivery method surcharge',
            ],
        ];

        $builderSpy->expects($this->any())
            ->method('addDocumentAllowanceCharge')
            ->willReturnCallback(function (...$actualArgs) use (&$expectedCallsStack, $builderSpy) {
                foreach ($expectedCallsStack as $key => $expected) {
                    if ($actualArgs === array_values($expected)) {
                        unset($expectedCallsStack[$key]);
                    }
                }
                return $builderSpy;
            });

        $sut = new BuilderPriceAdjustementsConfigurator();
        $result = $sut->configureBuilder($builderSpy, $invoiceDataStub);
        $this->assertSame($builderSpy, $result);

        $this->assertSame([], $expectedCallsStack);
    }

    #[Test]
    #[DataProvider('zeroAndNotZeroVatCasesDataProvider')]
    public function configuresPaymentPriceAdjustement(float $vat, string $expectedCode): void
    {
        $orderStub = $this->createMock(Order::class);

        $orderStub->method('getOrderPaymentPrice')
            ->willReturn(
                $this->createConfiguredStub(Price::class, [
                    'getNettoPrice' => $paymentNet = rand(10, 20),
                    'getVat' => $vat,
                ])
            );

        $orderStub->method('getOrderDeliveryPrice')->willReturn($this->createStub(Price::class));
        $orderStub->method('getOrderWrappingPrice')->willReturn($this->createStub(Price::class));

        $invoiceDataStub = $this->createConfiguredStub(InvoiceDataInterface::class, [
            'getOrder' => $orderStub,
        ]);

        $builderSpy = $this->createMock(ZugferdDocumentBuilder::class);

        $expectedCallsStack = [
            [
                'actualAmount' => (float)$paymentNet,
                'isCharge' => true,
                'taxCategoryCode' => $expectedCode,
                'taxTypeCode' => 'VAT',
                'rateApplicablePercent' => $vat,
                ...array_fill(0, 6, null),
                'reason' => 'Payment method surcharge',
            ],
        ];

        $builderSpy->expects($this->any())
            ->method('addDocumentAllowanceCharge')
            ->willReturnCallback(function (...$actualArgs) use (&$expectedCallsStack, $builderSpy) {
                foreach ($expectedCallsStack as $key => $expected) {
                    if ($actualArgs === array_values($expected)) {
                        unset($expectedCallsStack[$key]);
                    }
                }
                return $builderSpy;
            });

        $sut = new BuilderPriceAdjustementsConfigurator();
        $result = $sut->configureBuilder($builderSpy, $invoiceDataStub);
        $this->assertSame($builderSpy, $result);

        $this->assertSame([], $expectedCallsStack);
    }

    #[Test]
    #[DataProvider('zeroAndNotZeroVatCasesDataProvider')]
    public function configuresWrappingPriceAdjustement(float $vat, string $expectedCode): void
    {
        $orderStub = $this->createMock(Order::class);

        $orderStub->method('getOrderWrappingPrice')
            ->willReturn(
                $this->createConfiguredStub(Price::class, [
                    'getNettoPrice' => $wrappingNet = rand(10, 20),
                    'getVat' => $vat,
                ])
            );

        $orderStub->method('getOrderPaymentPrice')->willReturn($this->createStub(Price::class));
        $orderStub->method('getOrderDeliveryPrice')->willReturn($this->createStub(Price::class));

        $invoiceDataStub = $this->createConfiguredStub(InvoiceDataInterface::class, [
            'getOrder' => $orderStub,
        ]);

        $builderSpy = $this->createMock(ZugferdDocumentBuilder::class);

        $expectedCallsStack = [
            [
                'actualAmount' => (float)$wrappingNet,
                'isCharge' => true,
                'taxCategoryCode' => $expectedCode,
                'taxTypeCode' => 'VAT',
                'rateApplicablePercent' => $vat,
                ...array_fill(0, 6, null),
                'reason' => 'Wrapping surcharge',
            ],
        ];

        $builderSpy->expects($this->any())
            ->method('addDocumentAllowanceCharge')
            ->willReturnCallback(function (...$actualArgs) use (&$expectedCallsStack, $builderSpy) {
                foreach ($expectedCallsStack as $key => $expected) {
                    if ($actualArgs === array_values($expected)) {
                        unset($expectedCallsStack[$key]);
                    }
                }
                return $builderSpy;
            });

        $sut = new BuilderPriceAdjustementsConfigurator();
        $result = $sut->configureBuilder($builderSpy, $invoiceDataStub);
        $this->assertSame($builderSpy, $result);

        $this->assertSame([], $expectedCallsStack);
    }

    #[Test]
    public function configuresDiscounts(): void
    {
        $orderStub = $this->createMock(Order::class);
        $orderStub->method('getFieldData')
            ->willReturnMap([
                ['OXDISCOUNT', $discount = rand(10, 20)],
                ['OXVOUCHERDISCOUNT', $voucher = rand(10, 20)],
            ]);
        $orderStub->method('getOrderDeliveryPrice')->willReturn($this->createStub(Price::class));
        $orderStub->method('getOrderPaymentPrice')->willReturn($this->createStub(Price::class));
        $orderStub->method('getOrderWrappingPrice')->willReturn($this->createStub(Price::class));

        $invoiceDataStub = $this->createConfiguredStub(InvoiceDataInterface::class, [
            'getOrder' => $orderStub,
        ]);

        $builderSpy = $this->createMock(ZugferdDocumentBuilder::class);

        $expectedCallsStack = [
            [
                'actualAmount' => (float)$discount,
                'isCharge' => false,
                'taxCategoryCode' => 'Z',
                'taxTypeCode' => 'VAT',
                'rateApplicablePercent' => 0.0,
                ...array_fill(0, 6, null),
                'reason' => 'Discount',
            ],
            [
                'actualAmount' => (float)$voucher,
                'isCharge' => false,
                'taxCategoryCode' => 'Z',
                'taxTypeCode' => 'VAT',
                'rateApplicablePercent' => 0.0,
                ...array_fill(0, 6, null),
                'reason' => 'Voucher',
            ],
        ];

        $builderSpy->expects($this->any())
            ->method('addDocumentAllowanceCharge')
            ->willReturnCallback(function (...$actualArgs) use (&$expectedCallsStack, $builderSpy) {
                foreach ($expectedCallsStack as $key => $expected) {
                    if ($actualArgs === array_values($expected)) {
                        unset($expectedCallsStack[$key]);
                    }
                }
                return $builderSpy;
            });

        $sut = new BuilderPriceAdjustementsConfigurator();
        $result = $sut->configureBuilder($builderSpy, $invoiceDataStub);
        $this->assertSame($builderSpy, $result);

        $this->assertSame([], $expectedCallsStack);
    }
}
