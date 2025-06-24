<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Integration\Email\Traits;

use FreshAdvance\Invoice\Email\Traits\ServiceContainer;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Tests\Integration\IntegrationTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(ServiceContainer::class)]
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

        $this->assertInstanceOf(
            Registry::class,
            $sut->getTestService('FreshAdvance\Invoice\Core\Registry')
        );
    }
}
