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
use OxidEsales\Eshop\Core\Config;
use PHPUnit\Framework\TestCase;

class InvoiceTest extends TestCase
{
    public function testGetInvoiceData(): void
    {
        $orderStub = $this->createPartialMock(OrderModel::class, ['getShopId']);
        $orderStub->method('getShopId')->willReturn(3);
        $orderServiceStub = $this->createConfiguredMock(Order::class, [
            'getRequestOrder' => $orderStub
        ]);

        $shopStub = $this->createStub(ShopModel::class);
        $shopServiceStub = $this->createPartialMock(Shop::class, ['getShop']);
        $shopServiceStub->method('getShop')->with(3)->willReturn($shopStub);

        $shopConfigStub = $this->createPartialMock(Config::class, ['getShopConfVar']);
        $shopConfigStub->method('getShopConfVar')->with('sDefaultLang', 3)->willReturn(5);

        $sut = new Invoice(
            orderService: $orderServiceStub,
            shopService: $shopServiceStub,
            shopConfig: $shopConfigStub
        );

        $result = $sut->getInvoiceData();

        $this->assertSame($orderStub, $result->getOrder());
        $this->assertSame($shopStub, $result->getShop());
        $this->assertSame(5, $result->getLanguageId());
    }
}
