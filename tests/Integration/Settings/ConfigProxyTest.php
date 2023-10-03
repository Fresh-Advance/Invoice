<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Integration\Settings;

use FreshAdvance\Invoice\Settings\ConfigProxy;
use OxidEsales\Eshop\Core\Config;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FreshAdvance\Invoice\Settings\ConfigProxy
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

        $sut = new \FreshAdvance\Invoice\Settings\ConfigProxy($shopConfigMock);

        $this->assertSame(5, $sut->getShopDefaultLanguageId($shopId));
    }
}
