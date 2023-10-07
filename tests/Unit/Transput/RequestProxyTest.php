<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\Transput;

use FreshAdvance\Invoice\Transput\RequestProxy;
use OxidEsales\Eshop\Core\Request;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FreshAdvance\Invoice\Transput\RequestProxy
 */
class RequestProxyTest extends TestCase
{
    public function testGetOrderIdFromRequest(): void
    {
        $testValue = 'someExampleValue';

        $requestMock = $this->createPartialMock(Request::class, ['getRequestParameter']);
        $requestMock->method('getRequestParameter')->willReturnMap([
            [RequestProxy::REQUEST_PARAM_ORDER_ID, null, $testValue]
        ]);

        $sut = new RequestProxy($requestMock);

        $this->assertSame($testValue, $sut->getInvoiceIdFromRequest());
    }

    public function testGetInvoiceConfigurationFromRequest(): void
    {
        $formData = [
            'order_id' => 'someOrderId',
            'date' => 'someDate',
            'number' => 'someNumber',
            'signer' => 'someSigner',
        ];

        $requestMock = $this->createPartialMock(Request::class, ['getRequestParameter']);
        $requestMock->method('getRequestParameter')->willReturnMap([
            [RequestProxy::REQUEST_PARAM_INVOICE_DATA, null, $formData]
        ]);

        $sut = new RequestProxy($requestMock);
        $configuration = $sut->getInvoiceConfigurationFromRequest();

        $this->assertSame('someOrderId', $configuration->getOrderId());
        $this->assertSame('someDate', $configuration->getDate());
        $this->assertSame('someNumber', $configuration->getNumber());
        $this->assertSame('someSigner', $configuration->getSigner());
    }
}
