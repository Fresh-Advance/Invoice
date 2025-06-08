<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace Document\MpdfDocument;

use FreshAdvance\Invoice\Pdf\MpdfDocument\MpdfFactory;
use FreshAdvance\Invoice\Settings\ModuleSettingsInterface;
use Mpdf\Mpdf;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FreshAdvance\Invoice\Pdf\MpdfDocument\MpdfFactory
 */
class MpdfFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $sut = new MpdfFactory(
            moduleSettings: $this->createStub(ModuleSettingsInterface::class)
        );
        $this->assertInstanceOf(Mpdf::class, $sut->create());
    }

    #[DataProvider('archiveFlagDataProvider')]
    public function testArchiveFlagConfigured($value, $expected): void
    {
        $settingsStub = $this->createMock(ModuleSettingsInterface::class);
        $settingsStub->method('isForArchive')->willReturn($value);

        $sut = new MpdfFactory(
            moduleSettings: $settingsStub
        );
        $object = $sut->create();

        $this->assertSame($expected, $object->PDFA);
    }

    public static function archiveFlagDataProvider(): \Generator
    {
        yield 'configured' => [true, true];
        yield 'not configured' => [false, false];
    }
}
