<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Integration\Order\Repository;

use FreshAdvance\Invoice\Order\Exception\OrderNotFound;
use FreshAdvance\Invoice\Order\Repository\OrderRepository;
use OxidEsales\Eshop\Application\Model\Order as OrderModel;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidEsales\EshopCommunity\Tests\Integration\IntegrationTestCase;

/**
 * @covers \FreshAdvance\Invoice\Order\Repository\OrderRepository
 */
class OrderRepositoryTest extends IntegrationTestCase
{
    protected const TEST_ORDER_ID = 'someTestOrderId';
    protected const TEST_ORDER_ID_WRONG = 'someNotExistingOrderId';

    public function setUp(): void
    {
        parent::setUp();

        $testOrder = oxNew(OrderModel::class);
        $testOrder->setId(self::TEST_ORDER_ID);
        $testOrder->assign(
            ['oxbillnr' => 321]
        );
        $testOrder->save();
    }

    public function testGetOrder(): void
    {
        $sut = $this->getSut();

        $result = $sut->getByOrderId(self::TEST_ORDER_ID);

        $this->assertSame(self::TEST_ORDER_ID, $result->getId());
    }

    public function testGetWrongOrder(): void
    {
        $sut = $this->getSut();

        $this->expectException(OrderNotFound::class);
        $sut->getByOrderId(self::TEST_ORDER_ID_WRONG);
    }

    public function testOrderInvoiceNumberNotIncreasedIfAlreadySet(): void
    {
        $sut = $this->getSut();

        $order = $sut->getByOrderId(self::TEST_ORDER_ID);
        $sut->fillEmptyInvoiceNumber($order);

        $updatedOrder = $sut->getByOrderId(self::TEST_ORDER_ID);
        $this->assertEquals(321, $updatedOrder->getFieldData('oxbillnr'));
    }

    public function testOrderInvoiceNumberIncreasedIfNotYetSet(): void
    {
        $order = oxNew(OrderModel::class);
        $order->save();

        $sut = $this->getSut();
        $sut->fillEmptyInvoiceNumber($order);

        $updatedOrder = $sut->getByOrderId($order->getId());
        $this->assertEquals(322, $updatedOrder->getFieldData('oxbillnr'));
    }

    public function getSut(): \FreshAdvance\Invoice\Order\Repository\OrderRepositoryInterface
    {
        return new OrderRepository(
            queryBuilderFactory: $this->get(QueryBuilderFactoryInterface::class),
        );
    }
}
