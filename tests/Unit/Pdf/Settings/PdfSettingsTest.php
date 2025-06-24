<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\Pdf\Settings;

use FreshAdvance\Invoice\InvoiceData\Settings\FormatSettings;
use FreshAdvance\Invoice\Module;
use FreshAdvance\Invoice\Pdf\Settings\PdfSettings;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Facade\ModuleSettingServiceInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class PdfSettingsTest extends TestCase
{
    #[DataProvider('booleanDataProvider')]
    #[Test]
    public function isForArchive(bool $value): void
    {
        $mssMock = $this->createMock(ModuleSettingServiceInterface::class);
        $mssMock->method('getBoolean')->willReturnMap([
            [PdfSettings::SETTING_DOCUMENT_IS_FOR_ARCHIVE, Module::MODULE_ID, $value]
        ]);

        $sut = new PdfSettings($mssMock);
        $this->assertSame($value, $sut->isForArchive());
    }

    public static function booleanDataProvider(): array
    {
        return [
            [true],
            [false]
        ];
    }
}
