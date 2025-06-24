<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Integration;

use FreshAdvance\Invoice\InvoiceData\InvoiceConfiguration\Repository\InvoiceConfigurationRepositoryDecoration;
use FreshAdvance\Invoice\InvoiceData\InvoiceConfiguration\Repository\InvoiceConfigurationRepositoryInterface;
use OxidEsales\EshopCommunity\Internal\Container\ContainerBuilderFactory;
use OxidEsales\EshopCommunity\Tests\Integration\IntegrationTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

class ServiceAvailabilityTest extends IntegrationTestCase
{
    private static $cachedContainer;
    private static $decorations;

    public static function setUpBeforeClass(): void
    {
        $containerBuilder = (new ContainerBuilderFactory())->create();
        $container = $containerBuilder->getContainer();
        foreach ($container->getDefinitions() as $id => $definition) {
            $definition->setPublic(true);
            if ($decorated = $definition->getDecoratedService()) {
                self::$decorations[reset($decorated)][] = $id;
            }
        }
        $container->compile(true);

        self::$cachedContainer = $container;
    }

    #[DataProvider('serviceAvailabilityDataProvider')]
    #[Test]
    public function servicesAvailable(string $serviceName): void
    {
        $service = self::$cachedContainer->get($serviceName);
        $this->assertInstanceOf($serviceName, $service);
    }

    #[DataProvider('serviceDecorationProvider')]
    #[Test]
    public function servicesDecorated(string $serviceName, array $expectedDecorations): void
    {
        $decorations = self::$decorations[$serviceName];
        foreach ($expectedDecorations as $oneExpectedDecoration) {
            $this->assertContains($oneExpectedDecoration, $decorations);
        }
    }

    public static function serviceDecorationProvider(): \Generator
    {
        yield [
            'serviceName' => \FreshAdvance\Invoice\Pdf\InvoiceGeneratorInterface::class,
            'expectedDecorations' => [
                \FreshAdvance\Invoice\Order\Decoration\InvoiceGeneratorDecorator::class,
            ],
        ];

        yield [
            'serviceName' => \FreshAdvance\Invoice\Pdf\Service\FilenameCalculatorInterface::class,
            'expectedDecorations' => [
                \FreshAdvance\Invoice\Pdf\Service\FilenameLengthFilter::class,
                \FreshAdvance\Invoice\Pdf\Service\FilenameCharactersFilter::class,
            ],
        ];

        yield [
            'serviceName' => InvoiceConfigurationRepositoryInterface::class,
            'expectedDecorations' => [
                InvoiceConfigurationRepositoryDecoration::class,
            ],
        ];
    }

    public static function serviceAvailabilityDataProvider(): array
    {
        return [
            // Email
            [\FreshAdvance\Invoice\Email\Settings\EmailSettingsInterface::class],

            // InvoiceData
            [\FreshAdvance\Invoice\InvoiceData\Service\InvoiceDataServiceInterface::class],
            [\FreshAdvance\Invoice\InvoiceData\Service\InvoiceFileServiceInterface::class],
            [InvoiceConfigurationRepositoryInterface::class],
                [InvoiceConfigurationRepositoryDecoration::class],
            [\FreshAdvance\Invoice\InvoiceData\Shop\Repository\ShopRepositoryInterface::class],
            [\FreshAdvance\Invoice\InvoiceData\Transput\RequestInterface::class],

            // Language
            [\FreshAdvance\Invoice\Language\Service\LanguageInterface::class],
            [\FreshAdvance\Invoice\Language\Service\NumberWordingServiceInterface::class],

            // Order
            [\FreshAdvance\Invoice\Order\Repository\OrderRepositoryInterface::class],
            [\FreshAdvance\Invoice\Order\Service\OrderServiceInterface::class],
            [\FreshAdvance\Invoice\Order\Settings\OrderSettingsInterface::class],

            // Pdf
            [\FreshAdvance\Invoice\Pdf\InvoiceGeneratorInterface::class],
                [\FreshAdvance\Invoice\Order\Decoration\InvoiceGeneratorDecorator::class],
            [\FreshAdvance\Invoice\Pdf\Service\DocumentRendererInterface::class],
            [\FreshAdvance\Invoice\Pdf\Service\FormatCalculatorInterface::class],
            [\FreshAdvance\Invoice\Pdf\Service\FilenameCalculatorInterface::class],
                [\FreshAdvance\Invoice\Pdf\Service\FilenameLengthFilter::class],
                [\FreshAdvance\Invoice\Pdf\Service\FilenameCharactersFilter::class],
            [\FreshAdvance\Invoice\Pdf\Service\TemplateParametersServiceInterface::class],
            [\FreshAdvance\Invoice\Pdf\Settings\DocumentLayoutSettingsInterface::class],

            // Settings
            [\FreshAdvance\Invoice\InvoiceData\Settings\ContextInterface::class],
            [\FreshAdvance\Invoice\InvoiceData\Settings\ModuleSettingsInterface::class],
            [\FreshAdvance\Invoice\InvoiceData\Settings\ConfigInterface::class],

            // Transput
            [\FreshAdvance\Invoice\Transput\ResponseInterface::class],
        ];
    }
}
