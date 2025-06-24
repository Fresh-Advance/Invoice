<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Integration\Pdf\MpdfDocument;

use FreshAdvance\Invoice\Pdf\MpdfDocument\MpdfFactory;
use FreshAdvance\Invoice\Pdf\Settings\PdfSettingsInterface;
use Mpdf\Mpdf;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(MpdfFactory::class)]
class MpdfFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $sut = new MpdfFactory(
            moduleSettings: $this->createStub(PdfSettingsInterface::class)
        );
        $this->assertInstanceOf(Mpdf::class, $sut->create());
    }

    #[DataProvider('archiveFlagDataProvider')]
    public function testArchiveFlagConfigured($value, $expected): void
    {
        $settingsStub = $this->createMock(PdfSettingsInterface::class);
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
