<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\Service;

use FreshAdvance\Invoice\DataType\InvoiceDataInterface;
use FreshAdvance\Invoice\Repository\OrderRepositoryInterface;
use FreshAdvance\Invoice\Service\OrderService;
use FreshAdvance\Invoice\Service\OrderServiceInterface;
use OxidEsales\Eshop\Application\Model\Order;
use PHPUnit\Framework\TestCase;

class OrdereServiceTest extends TestCase
{
    public function testsomthing(): void
    {
        $invoiceData = $this->createConfiguredMock(InvoiceDataInterface::class, [
            'getOrder' => $orderStub = $this->createStub(Order::class)
        ]);

        $sut = $this->getSut(
            orderRepository: $repositorySpy = $this->createMock(OrderRepositoryInterface::class)
        );

        $repositorySpy->expects($this->once())
            ->method('fillEmptyInvoiceNumber')
            ->with($orderStub);

        $sut->prepareOrderInvoiceNumber($invoiceData);
    }

    public function getSut(
        OrderRepositoryInterface $orderRepository = null
    ): OrderServiceInterface {
        return new OrderService(
            orderRepository: $orderRepository ?? $this->createStub(OrderRepositoryInterface::class)
        );
    }
}
