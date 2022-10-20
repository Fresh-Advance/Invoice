<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\Transition\Controller\Admin;

use FreshAdvance\Invoice\Service\Order;
use FreshAdvance\Invoice\Transition\Controller\Admin\InvoiceController;
use OxidEsales\Eshop\Application\Model\Order as OrderModel;
use PHPUnit\Framework\TestCase;

class InvoiceControllerTest extends TestCase
{
    public function testRender(): void
    {
        $orderModelStub = $this->createStub(OrderModel::class);
        $orderServiceMock = $this->createPartialMock(Order::class, ['getOrder']);
        $orderServiceMock->method('getOrder')->willReturnMap([
            ['someOxid', $orderModelStub]
        ]);

        $sut = $this->createPartialMock(InvoiceController::class, ['getServiceFromContainer']);
        $sut->method('getServiceFromContainer')->willReturnMap([
            [Order::class, $orderServiceMock]
        ]);

        $_GET['oxid'] = 'someOxid';

        $this->assertStringStartsWith('@fa_invoice/admin/', $sut->render());

        $viewData = $sut->getViewData();
        $this->assertSame($orderModelStub, $viewData['order']);
    }
}
