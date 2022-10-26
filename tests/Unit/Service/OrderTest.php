<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\Service;

use FreshAdvance\Invoice\Exception\RequestParameterMissing;
use FreshAdvance\Invoice\Service\Order;
use OxidEsales\Eshop\Application\Model\Order as OrderModel;
use OxidEsales\Eshop\Core\Request;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FreshAdvance\Invoice\Service\Order
 */
class OrderTest extends TestCase
{
    public function testGetRequestOrder(): void
    {
        $request = $this->createPartialMock(Request::class, ['getRequestParameter']);
        $request->method('getRequestParameter')->willReturnMap([
            ['orderId', null, 'someOrderId']
        ]);

        $orderStub = $this->createStub(OrderModel::class);

        $sut = $this->getMockBuilder(Order::class)
            ->setConstructorArgs(['request' => $request])
            ->onlyMethods(['getOrder'])
            ->getMock();
        $sut->method('getOrder')->with('someOrderId')->willReturn($orderStub);

        $this->assertSame($orderStub, $sut->getRequestOrder());
    }

    public function testGetRequestOrderFailsOnRequestParamMissing(): void
    {
        $request = $this->createPartialMock(Request::class, ['getRequestParameter']);
        $request->method('getRequestParameter')->willReturn(null);

        $sut = new Order(request: $request);

        $this->expectException(RequestParameterMissing::class);
        $sut->getRequestOrder();
    }
}
