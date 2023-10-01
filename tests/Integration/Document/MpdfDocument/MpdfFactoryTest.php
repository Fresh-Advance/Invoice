<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace Document\MpdfDocument;

use FreshAdvance\Invoice\Document\MpdfDocument\MpdfFactory;
use FreshAdvance\Invoice\Service\ModuleSettingsInterface;
use Mpdf\Mpdf;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FreshAdvance\Invoice\Document\MpdfDocument\MpdfFactory
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

    public function testArchiveFlagConfigured(): void
    {
        $settingsStub = $this->createMock(ModuleSettingsInterface::class);
        $settingsStub->method('isForArchive')->willReturn(true);

        $sut = new MpdfFactory(
            moduleSettings: $settingsStub
        );
        $object = $sut->create();

        $this->assertSame(true, $object->PDFA);
    }
}
