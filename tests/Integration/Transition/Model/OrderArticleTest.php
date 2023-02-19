<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\Transition\Model;

use FreshAdvance\Invoice\Transition\Model\OrderArticle;
use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Core\Model\BaseModel;
use OxidEsales\EshopCommunity\Tests\Integration\IntegrationTestCase;

/**
 * @covers \FreshAdvance\Invoice\Transition\Model\OrderArticle
 */
class OrderArticleTest extends IntegrationTestCase
{
    public function testFaGetTranslatedTitleNoParent(): void
    {
        $testProductId = 'testProductOxid';
        $testProduct = oxNew(BaseModel::class);
        $testProduct->init('oxarticles');
        $testProduct->assign([
            'oxid' => $testProductId,
            'OXTITLE' => 'someTitle0',
            'OXTITLE_1' => 'someTitle1',
            'oxparentid' => '',
            'oxshopid' => 1
        ]);
        $testProduct->save();

        $orderArticle = $this->createPartialMock(OrderArticle::class, ['getParentId', 'getProductId']);
        $orderArticle->method('getParentId')->willReturn('');
        $orderArticle->method('getProductId')->willReturn($testProductId);

        $this->assertSame('someTitle1', $orderArticle->faGetTranslatedTitle(1));
    }

    public function testFaGetTranslatedTitleWithOverwrittenParent(): void
    {
        $testParentProductId = 'testParentProductOxid';
        $testParentProduct = oxNew(BaseModel::class);
        $testParentProduct->init('oxarticles');
        $testParentProduct->assign([
            'oxid' => $testParentProductId,
            'OXTITLE' => 'someParentTitle0',
            'OXTITLE_1' => 'someParentTitle1',
            'oxparentid' => '',
            'oxshopid' => 1
        ]);
        $testParentProduct->save();

        $testProductId = 'testProductOxid';
        $testProduct = oxNew(BaseModel::class);
        $testProduct->init('oxarticles');
        $testProduct->assign([
            'oxid' => $testProductId,
            'OXTITLE' => 'someTitle0',
            'OXTITLE_1' => 'someTitle1',
            'oxparentid' => $testParentProductId,
            'oxshopid' => 1
        ]);
        $testProduct->save();

        $orderArticle = $this->createPartialMock(OrderArticle::class, ['getParentId', 'getProductId']);
        $orderArticle->method('getParentId')->willReturn($testParentProductId);
        $orderArticle->method('getProductId')->willReturn($testProductId);

        $this->assertSame('someTitle1', $orderArticle->faGetTranslatedTitle(1));
    }

    public function testFaGetTranslatedTitleWithNotOverwrittenParent(): void
    {
        $testParentProductId = 'testParentProductOxid';
        $testParentProduct = oxNew(BaseModel::class);
        $testParentProduct->init('oxarticles');
        $testParentProduct->assign([
            'oxid' => $testParentProductId,
            'OXTITLE' => 'someParentTitle0',
            'OXTITLE_1' => 'someParentTitle1',
            'oxparentid' => '',
            'oxshopid' => 1
        ]);
        $testParentProduct->save();

        $testProductId = 'testProductOxid';
        $testProduct = oxNew(BaseModel::class);
        $testProduct->init('oxarticles');
        $testProduct->assign([
            'oxid' => $testProductId,
            'OXTITLE' => '',
            'OXTITLE_1' => '',
            'oxparentid' => $testParentProductId,
            'oxshopid' => 1
        ]);
        $testProduct->save();

        $orderArticle = $this->createPartialMock(OrderArticle::class, ['getParentId', 'getProductId']);
        $orderArticle->method('getParentId')->willReturn($testParentProductId);
        $orderArticle->method('getProductId')->willReturn($testProductId);

        $this->assertSame('someParentTitle1', $orderArticle->faGetTranslatedTitle(1));
    }
}
