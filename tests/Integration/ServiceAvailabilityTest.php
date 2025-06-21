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
        $container->get('oxid_esales.module.install.service.launched_shop_project_configuration_generator')->generate();

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
            // todo: find out how to check the module settings using instance
//            [\FreshAdvance\Invoice\Pdf\InvoiceGeneratorInterface::class],
//                [\FreshAdvance\Invoice\Order\Decoration\InvoiceGeneratorDecorator::class],
            [\FreshAdvance\Invoice\Pdf\Service\DocumentRendererInterface::class],
            [\FreshAdvance\Invoice\Pdf\Service\FormatCalculatorInterface::class],
            [\FreshAdvance\Invoice\Pdf\Service\FilenameCalculatorInterface::class],
                [\FreshAdvance\Invoice\Pdf\Service\FilenameLengthFilter::class],
                [\FreshAdvance\Invoice\Pdf\Service\FilenameCharactersFilter::class],
            [\FreshAdvance\Invoice\Pdf\Service\TemplateParametersServiceInterface::class],
            [\FreshAdvance\Invoice\Pdf\Settings\DocumentLayoutSettingsInterface::class],

            // todo: move shared repository items to where they belong.
            [\FreshAdvance\Invoice\Repository\InvoiceConfigurationRepositoryInterface::class],
                [\FreshAdvance\Invoice\Repository\InvoiceConfigurationRepositoryDecoration::class],
            [\FreshAdvance\Invoice\Repository\ShopRepositoryInterface::class],

            // todo: move shared service items to where they belong.
            [\FreshAdvance\Invoice\Service\Invoice::class],
            [\FreshAdvance\Invoice\Service\InvoiceServiceInterface::class],

            // Settings
            [\FreshAdvance\Invoice\Settings\ContextInterface::class],
            [\FreshAdvance\Invoice\Settings\ModuleSettingsInterface::class],
            [\FreshAdvance\Invoice\Settings\ConfigInterface::class],

            // Transput
            [\FreshAdvance\Invoice\Transput\RequestInterface::class],
            [\FreshAdvance\Invoice\Transput\UtilsInterface::class],
        ];
    }
}
