<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace ElectronicInvoice\ZUGFeRD\Service;

use Codeception\PHPUnit\TestCase;
use FreshAdvance\Invoice\ElectronicInvoice\ZUGFeRD\Factory\ZugferdPdfMergerFactoryInterface;
use FreshAdvance\Invoice\ElectronicInvoice\ZUGFeRD\Service\ZugferdPdfMerger;
use FreshAdvance\Invoice\ElectronicInvoice\ZUGFeRD\Service\ZugferdPdfMergerInterface;
use horstoeko\zugferd\ZugferdDocumentPdfMerger;
use PHPUnit\Framework\Attributes\Test;

class ZugferdPdfMergerTest extends TestCase
{
    #[Test]
    public function mergerCreatedAndFileOverwriteTriggered(): void
    {
        $pdfFilePath = uniqid();
        $xmlExample = uniqid();

        $mergerSpy = $this->createMock(ZugferdDocumentPdfMerger::class);
        $mergerSpy->expects($this->once())
            ->method('generateDocument');
        $mergerSpy->expects($this->once())
            ->method('saveDocument')
            ->with($pdfFilePath);

        $mergerFactoryMock = $this->createMock(ZugferdPdfMergerFactoryInterface::class);
        $mergerFactoryMock->method('createPdfMerger')
            ->with($xmlExample, $pdfFilePath)
            ->willReturn($mergerSpy);

        $sut = $this->getSut(
            mergerFactory: $mergerFactoryMock,
        );

        $sut->mergeXmlToPdf($xmlExample, $pdfFilePath);
    }

    public function getSut(
        ZugferdPdfMergerFactoryInterface $mergerFactory = null,
    ): ZugferdPdfMergerInterface {
        return new ZugferdPdfMerger(
            mergerFactory: $mergerFactory ?? $this->createStub(ZugferdPdfMergerFactoryInterface::class),
        );
    }
}
