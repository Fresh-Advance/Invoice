<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace Document\MpdfDocument;

use FreshAdvance\Invoice\Document\MpdfDocument\MpdfFactory;
use Mpdf\Mpdf;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FreshAdvance\Invoice\Document\MpdfDocument\MpdfFactory
 */
class MpdfFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $sut = new MpdfFactory();
        $this->assertInstanceOf(Mpdf::class, $sut->create());
    }
}
