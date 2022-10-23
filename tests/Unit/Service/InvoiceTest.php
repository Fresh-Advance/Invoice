<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\Service;

use FreshAdvance\Invoice\Service\Invoice;
use FreshAdvance\Invoice\Service\Order;
use FreshAdvance\Invoice\Service\Shop;
use OxidEsales\Eshop\Application\Model\Order as OrderModel;
use OxidEsales\Eshop\Application\Model\Shop as ShopModel;
use PHPUnit\Framework\TestCase;

class InvoiceTest extends TestCase
{
    public function testGetInvoiceData(): void
    {
        $orderStub = $this->createStub(OrderModel::class);
        $orderServiceStub = $this->createConfiguredMock(Order::class, [
            'getRequestOrder' => $orderStub
        ]);

        $shopStub = $this->createStub(ShopModel::class);
        $shopServiceStub = $this->createConfiguredMock(Shop::class, [
            'getActiveShop' => $shopStub
        ]);

        $sut = new Invoice(
            orderService: $orderServiceStub,
            shopService: $shopServiceStub
        );

        $result = $sut->getInvoiceData();

        $this->assertSame($orderStub, $result->getOrder());
        $this->assertSame($shopStub, $result->getShop());
    }
}
