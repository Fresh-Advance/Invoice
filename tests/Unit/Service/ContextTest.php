<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\Service;

use FreshAdvance\Invoice\Service\Context;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FreshAdvance\Invoice\Service\Context
 */
class ContextTest extends TestCase
{
    public function testGetInvoicesPath(): void
    {
        $basicContextStub = $this->createConfiguredMock(
            \OxidEsales\EshopCommunity\Internal\Transition\Utility\BasicContextInterface::class,
            ['getShopRootPath' => 'someShopPath']
        );

        $sut = new Context($basicContextStub);
        $this->assertSame('someShopPath/' . $sut::INVOICES_PARTIAL_PATH, $sut->getInvoicesPath());
    }
}
