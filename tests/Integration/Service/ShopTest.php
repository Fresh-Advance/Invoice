<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Integration\Service;

use FreshAdvance\Invoice\Exception\ShopNotFound;
use FreshAdvance\Invoice\Service\Shop;
use OxidEsales\Eshop\Application\Model\Shop as ShopModel;
use OxidEsales\EshopCommunity\Internal\Transition\Utility\ContextInterface;
use OxidEsales\EshopCommunity\Tests\Integration\IntegrationTestCase;

class ShopTest extends IntegrationTestCase
{
    protected const TEST_SHOP_ID = '5';
    protected const TEST_SHOP_ID_WRONG = '100';

    public function setUp(): void
    {
        parent::setUp();

        $testShop = oxNew(ShopModel::class);
        $testShop->setId(self::TEST_SHOP_ID);
        $testShop->save();
    }

    public function testGetShop(): void
    {
        $sut = $this->createPartialMock(Shop::class, []);
        $result = $sut->getShop(self::TEST_SHOP_ID);

        $this->assertSame(self::TEST_SHOP_ID, $result->getId());
    }

    public function testGetNotExistingShop(): void
    {
        $sut = $this->createPartialMock(Shop::class, []);

        $this->expectException(ShopNotFound::class);
        $sut->getShop(self::TEST_SHOP_ID_WRONG);
    }
}
