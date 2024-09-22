<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Integration\Order\Decoration;

use FreshAdvance\Invoice\Document\InvoiceGeneratorInterface;
use FreshAdvance\Invoice\Order\Decoration\InvoiceGeneratorDecorator;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Tests\Integration\IntegrationTestCase;

/** @covers \FreshAdvance\Invoice\Order\Decoration\InvoiceGeneratorDecorator */
class InvoiceGeneratorDecoratorTest extends IntegrationTestCase
{
    public function testOriginalServiceIsDecorated(): void
    {
        $sut = ContainerFactory::getInstance()->getContainer()->get(InvoiceGeneratorInterface::class);

        $this->assertInstanceOf(InvoiceGeneratorDecorator::class, $sut);
    }
}
