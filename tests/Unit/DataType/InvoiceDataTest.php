<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\DataType;

use FreshAdvance\Invoice\DataType\InvoiceConfigurationInterface;
use FreshAdvance\Invoice\DataType\InvoiceData;
use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Application\Model\Shop;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FreshAdvance\Invoice\DataType\InvoiceData
 */
class InvoiceDataTest extends TestCase
{
    public function testMethods(): void
    {
        $orderStub = $this->createStub(Order::class);
        $shopStub = $this->createStub(Shop::class);
        $invoiceConfigurationStub = $this->createStub(InvoiceConfigurationInterface::class);

        $sut = new InvoiceData(
            order: $orderStub,
            shop: $shopStub,
            invoicePath: 'somePath',
            invoiceConfiguration: $invoiceConfigurationStub
        );

        $this->assertSame($orderStub, $sut->getOrder());
        $this->assertSame($shopStub, $sut->getShop());
        $this->assertSame(0, $sut->getLanguageId());
        $this->assertSame('somePath', $sut->getInvoicePath());
        $this->assertSame($invoiceConfigurationStub, $sut->getInvoiceConfiguration());
    }

    public function testGetLanguage(): void
    {
        $sut = new InvoiceData(
            order: $this->createStub(Order::class),
            shop: $this->createStub(Shop::class),
            invoicePath: 'somePath',
            invoiceConfiguration: $this->createStub(InvoiceConfigurationInterface::class),
            languageId: 10
        );

        $this->assertSame(10, $sut->getLanguageId());
    }

    public function getFilenameDataProvider(): array
    {
        return [
            ['orderNumber' => '', 'expected' => 'invoice.pdf'],
            ['orderNumber' => 'someNumber', 'expected' => 'invoice-someNumber.pdf'],
        ];
    }
}
