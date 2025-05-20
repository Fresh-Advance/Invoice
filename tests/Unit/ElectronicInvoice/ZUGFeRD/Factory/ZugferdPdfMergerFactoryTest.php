<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\ElectronicInvoice\ZUGFeRD\Factory;

use Codeception\PHPUnit\TestCase;
use FreshAdvance\Invoice\ElectronicInvoice\ZUGFeRD\Factory\ZugferdPdfMergerFactory;
use FreshAdvance\Invoice\ElectronicInvoice\ZUGFeRD\Factory\ZugferdXmlBuilderFactory;
use horstoeko\zugferd\ZugferdDocumentBuilder;
use horstoeko\zugferd\ZugferdDocumentPdfMerger;
use PHPUnit\Framework\Attributes\Test;

class ZugferdPdfMergerFactoryTest extends TestCase
{
    #[Test]
    public function createPdfMergerWithSameParamsReturnsNewMerger(): void
    {
        $sut = new ZugferdPdfMergerFactory();

        $xml = uniqid();
        $pdfFilePath = uniqid();

        $merger1 = $sut->createPdfMerger($xml, $pdfFilePath);
        $merger2 = $sut->createPdfMerger($xml, $pdfFilePath);

        $this->assertInstanceOf(ZugferdDocumentPdfMerger::class, $merger1);
        $this->assertInstanceOf(ZugferdDocumentPdfMerger::class, $merger2);

        $this->assertNotSame($merger1, $merger2);
    }
}
