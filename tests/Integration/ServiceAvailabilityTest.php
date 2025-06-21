<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Integration;

use OxidEsales\EshopCommunity\Tests\Integration\IntegrationTestCase;
use OxidEsales\EshopCommunity\Tests\TestContainerFactory;
use PHPUnit\Framework\Attributes\DataProvider;

class ServiceAvailabilityTest extends IntegrationTestCase
{
    private static $cachedContainer;

    public static function setUpBeforeClass(): void
    {
        $container = (new TestContainerFactory())->create();
        $container->compile(true);

        self::$cachedContainer = $container;
    }

    #[DataProvider('serviceAvailabilityDataProvider')]
    public function testServiceAvailability(string $serviceName): void
    {
        $service = self::$cachedContainer->get($serviceName);
        $this->assertInstanceOf($serviceName, $service);
    }

    public static function serviceAvailabilityDataProvider(): array
    {
        return [
            // Email
            [\FreshAdvance\Invoice\Email\Settings\EmailSettingsInterface::class],

            // Language
            [\FreshAdvance\Invoice\Language\Service\LanguageInterface::class],
            [\FreshAdvance\Invoice\Language\Service\NumberWordingServiceInterface::class],

            // Order
            [\FreshAdvance\Invoice\Order\Repository\OrderRepositoryInterface::class],
            [\FreshAdvance\Invoice\Order\Service\OrderServiceInterface::class],
            [\FreshAdvance\Invoice\Order\Settings\OrderSettingsInterface::class],

            // Pdf
//            [\FreshAdvance\Invoice\Pdf\InvoiceGeneratorInterface::class],
//                [\FreshAdvance\Invoice\Order\Decoration\InvoiceGeneratorDecorator::class],
        ];
    }
}
