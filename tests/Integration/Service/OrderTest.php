<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Integration\Service;

use FreshAdvance\Invoice\Exception\OrderNotFound;
use FreshAdvance\Invoice\Service\Order;
use OxidEsales\Eshop\Application\Model\Order as OrderModel;
use OxidEsales\EshopCommunity\Tests\Integration\IntegrationTestCase;

/**
 * @covers \FreshAdvance\Invoice\Service\Order
 */
class OrderTest extends IntegrationTestCase
{
    protected const TEST_ORDER_ID = 'someTestOrderId';
    protected const TEST_ORDER_ID_WRONG = 'someNotExistingOrderId';

    public function setUp(): void
    {
        parent::setUp();

        $testOrder = oxNew(OrderModel::class);
        $testOrder->setId(self::TEST_ORDER_ID);
        $testOrder->save();
    }

    public function testGetOrder(): void
    {
        $sut = $this->createPartialMock(Order::class, []);
        $result = $sut->getOrder(self::TEST_ORDER_ID);

        $this->assertSame(self::TEST_ORDER_ID, $result->getId());
    }

    public function testGetWrongOrder(): void
    {
        $sut = $this->createPartialMock(Order::class, []);

        $this->expectException(OrderNotFound::class);
        $sut->getOrder(self::TEST_ORDER_ID_WRONG);
    }
}
