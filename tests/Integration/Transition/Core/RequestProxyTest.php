<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace Transition\Core;

use FreshAdvance\Invoice\Transput\RequestProxy;
use OxidEsales\Eshop\Core\Request;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FreshAdvance\Invoice\Transput\RequestProxy
 */
class RequestProxyTest extends TestCase
{
    public function testGetRequestEscapedParameter(): void
    {
        $testKey = 'exampleKey';
        $testValue = 'someExampleValue';

        $requestMock = $this->createPartialMock(Request::class, ['getRequestEscapedParameter']);
        $requestMock->expects($this->once())
            ->method('getRequestEscapedParameter')
            ->with($testKey)
            ->willReturn($testValue);

        $sut = new RequestProxy($requestMock);

        $this->assertSame($testValue, $sut->getRequestEscapedParameter($testKey));
    }

    public function testGetRequestParameter(): void
    {
        $testKey = 'exampleKey';
        $testValue = 'someExampleValue';
        $defaultValue = 'someDefault';

        $requestMock = $this->createPartialMock(Request::class, ['getRequestParameter']);
        $requestMock->expects($this->once())
            ->method('getRequestParameter')
            ->with($testKey, $defaultValue)
            ->willReturn($testValue);

        $sut = new RequestProxy($requestMock);

        $this->assertSame($testValue, $sut->getRequestParameter($testKey, $defaultValue));
    }
}
