<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace Email\Traits;

use FreshAdvance\Invoice\InvoiceData\Service\Invoice;
use OxidEsales\EshopCommunity\Tests\Integration\IntegrationTestCase;

/**
 * @covers \FreshAdvance\Invoice\Email\Traits\ServiceContainer
 */
class ServiceContainerTest extends IntegrationTestCase
{
    public function testGetServiceFromContainer()
    {
        $sut = new class {
            use \FreshAdvance\Invoice\Email\Traits\ServiceContainer;

            public function getTestService(string $service)
            {
                return $this->getServiceFromContainer($service);
            }
        };

        $this->assertInstanceOf(Invoice::class, $sut->getTestService(Invoice::class));
    }
}
