<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Integration\Traits;

use FreshAdvance\Invoice\Service\ContextInterface;
use FreshAdvance\Invoice\Traits\ServiceContainer;
use OxidEsales\EshopCommunity\Tests\Integration\IntegrationTestCase;

/**
 * @covers \FreshAdvance\Invoice\Traits\ServiceContainer
 */
class ServiceContainerTest extends IntegrationTestCase
{
    public function testGetServiceFromContainer()
    {
        $sut = new class {
            use ServiceContainer;

            public function getTestService(string $service)
            {
                return $this->getServiceFromContainer($service);
            }
        };

        $this->assertInstanceOf(ContextInterface::class, $sut->getTestService(ContextInterface::class));
    }
}
