<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\Settings\Service;

use FreshAdvance\Invoice\Module;
use FreshAdvance\Invoice\Settings\Service\DocumentLayoutSettingsService;
use FreshAdvance\Invoice\Settings\Service\DocumentLayoutSettingsServiceInterface;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\String\UnicodeString;

/**
 * @covers \FreshAdvance\Invoice\Settings\Service\DocumentLayoutSettingsService
 */
class DocumentLayoutSettingsTest extends TestCase
{
    /** @dataProvider settingsDataProvider */
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

    public function settingsDataProvider(): \Generator
    {
        yield 'margin top' => [
            'settingKey' => DocumentLayoutSettingsService::SETTING_MARGIN_TOP,
            'method' => 'getMarginTop',
        ];

        yield 'margin bottom' => [
            'settingKey' => DocumentLayoutSettingsService::SETTING_MARGIN_BOTTOM,
            'method' => 'getMarginBottom',
        ];

        yield 'margin left' => [
            'settingKey' => DocumentLayoutSettingsService::SETTING_MARGIN_LEFT,
            'method' => 'getMarginLeft',
        ];

        yield 'margin right' => [
            'settingKey' => DocumentLayoutSettingsService::SETTING_MARGIN_RIGHT,
            'method' => 'getMarginRight',
        ];

        yield 'document header' => [
            'settingKey' => DocumentLayoutSettingsService::SETTING_DOCUMENT_HEADER,
            'method' => 'getDocumentHeader',
        ];

        yield 'document footer' => [
            'settingKey' => DocumentLayoutSettingsService::SETTING_DOCUMENT_FOOTER,
            'method' => 'getDocumentFooter',
        ];
    }

    protected function getSut(
        ModuleSettingServiceInterface $moduleSettingService = null,
    ): DocumentLayoutSettingsServiceInterface {
        return new DocumentLayoutSettingsService(
            moduleSettingService: $moduleSettingService ?? $this->createStub(ModuleSettingServiceInterface::class),
        );
    }
}
