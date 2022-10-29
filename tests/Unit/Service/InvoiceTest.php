<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\Service;

use FreshAdvance\Invoice\Service\Context;
use FreshAdvance\Invoice\Service\Invoice;
use FreshAdvance\Invoice\Service\Order;
use FreshAdvance\Invoice\Service\Shop;
use OxidEsales\Eshop\Application\Model\Order as OrderModel;
use OxidEsales\Eshop\Application\Model\Shop as ShopModel;
use OxidEsales\Eshop\Core\Config;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FreshAdvance\Invoice\Service\Invoice
 */
class InvoiceTest extends TestCase
{
    public function testGetInvoiceData(): void
    {
        $orderStub = $this->createConfiguredMock(OrderModel::class, [
            'getShopId' => 3,
            'getId' => 'someOrderId'
        ]);

        $orderServiceStub = $this->createPartialMock(Order::class, ['getOrder']);
        $orderServiceStub->method('getOrder')->with('someOrderId')->willReturn($orderStub);

        $shopStub = $this->createStub(ShopModel::class);
        $shopServiceStub = $this->createPartialMock(Shop::class, ['getShop']);
        $shopServiceStub->method('getShop')->with(3)->willReturn($shopStub);

        $shopConfigStub = $this->createPartialMock(Config::class, ['getShopConfVar']);
        $shopConfigStub->method('getShopConfVar')->with('sDefaultLang', 3)->willReturn(5);

        $sut = new Invoice(
            orderService: $orderServiceStub,
            shopService: $shopServiceStub,
            shopConfig: $shopConfigStub,
            moduleContext: $this->createConfiguredMock(
                Context::class,
                ['getInvoicesPath' => 'someRootPath']
            )
        );

        $result = $sut->getInvoiceDataByOrderId('someOrderId');

        $this->assertSame($orderStub, $result->getOrder());
        $this->assertSame($shopStub, $result->getShop());
        $this->assertSame(5, $result->getLanguageId());
        $this->assertSame('someRootPath/so/someOrderId.pdf', $result->getInvoicePath());
    }
}
