<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\DataType;

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

        $sut = new InvoiceData(
            order: $orderStub,
            shop: $shopStub
        );

        $this->assertSame($orderStub, $sut->getOrder());
        $this->assertSame($shopStub, $sut->getShop());
    }
}
