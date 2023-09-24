<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace Transition\Core;

use FreshAdvance\Invoice\Transition\Core\ConfigProxy;
use OxidEsales\Eshop\Core\Config;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FreshAdvance\Invoice\Transition\Core\ConfigProxy
 */
class ConfigProxyTest extends TestCase
{
    public function testGetShopConfigValue(): void
    {
        $shopId = 3;
        $testKey = 'sDefaultLang';
        $testValue = '5';

        $shopConfigMock = $this->createPartialMock(Config::class, ['getShopConfVar']);
        $shopConfigMock->expects($this->once())
            ->method('getShopConfVar')
            ->with($testKey, $shopId)
            ->willReturn($testValue);

        $sut = new ConfigProxy($shopConfigMock);

        $this->assertSame(5, $sut->getShopDefaultLanguageId($shopId));
    }
}
