<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\Document\MpdfDocument;

use FreshAdvance\Invoice\Document\MpdfDocument\PdfData;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FreshAdvance\Invoice\Document\MpdfDocument\PdfData
 */
class PdfDataTest extends TestCase
{
    public function testMinimalContent(): void
    {
        $sut = new PdfData(
            htmlContent: 'someContent'
        );

        $this->assertSame('someContent', $sut->getContent());
        $this->assertNull($sut->getHeader());
        $this->assertNull($sut->getFooter());
    }

    public function testFullContent(): void
    {
        $sut = new PdfData(
            htmlContent: 'someContent',
            htmlHeader: 'someHeader',
            htmlFooter: 'someFooter'
        );

        $this->assertSame('someContent', $sut->getContent());
        $this->assertSame('someHeader', $sut->getHeader());
        $this->assertSame('someFooter', $sut->getFooter());
    }
}
