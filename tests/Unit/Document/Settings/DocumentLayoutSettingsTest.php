<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\Document\Settings;

use FreshAdvance\Invoice\Document\Settings\DocumentLayoutSettings;
use FreshAdvance\Invoice\Module;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\String\UnicodeString;

/**
 * @covers \FreshAdvance\Invoice\Document\Settings\DocumentLayoutSettings
 */
class DocumentLayoutSettingsTest extends TestCase
{
    #[DataProvider('settingsDataProvider')]
    public function testGetMarginTopReturnsModuleSettingValues(string $settingKey, string $method): void
    {
        $configValue = uniqid();

        $sut = $this->getSut(
            moduleSettingService: $moduleSettingService = $this->createMock(ModuleSettingServiceInterface::class),
        );

        $moduleSettingService->method('getString')
            ->with($settingKey, Module::MODULE_ID)
            ->willReturn(new UnicodeString($configValue));

        $this->assertSame($configValue, $sut->$method());
    }

    public static function settingsDataProvider(): \Generator
    {
        yield 'margin top' => [
            'settingKey' => DocumentLayoutSettings::SETTING_MARGIN_TOP,
            'method' => 'getMarginTop',
        ];

        yield 'margin bottom' => [
            'settingKey' => DocumentLayoutSettings::SETTING_MARGIN_BOTTOM,
            'method' => 'getMarginBottom',
        ];

        yield 'margin left' => [
            'settingKey' => DocumentLayoutSettings::SETTING_MARGIN_LEFT,
            'method' => 'getMarginLeft',
        ];

        yield 'margin right' => [
            'settingKey' => DocumentLayoutSettings::SETTING_MARGIN_RIGHT,
            'method' => 'getMarginRight',
        ];

        yield 'document header' => [
            'settingKey' => DocumentLayoutSettings::SETTING_DOCUMENT_HEADER,
            'method' => 'getDocumentHeader',
        ];

        yield 'document footer' => [
            'settingKey' => DocumentLayoutSettings::SETTING_DOCUMENT_FOOTER,
            'method' => 'getDocumentFooter',
        ];
    }

    protected function getSut(
        ModuleSettingServiceInterface $moduleSettingService = null,
    ): \FreshAdvance\Invoice\Document\Settings\DocumentLayoutSettingsInterface {
        return new DocumentLayoutSettings(
            moduleSettingService: $moduleSettingService ?? $this->createStub(ModuleSettingServiceInterface::class),
        );
    }
}
