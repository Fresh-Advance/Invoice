<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\ElectronicInvoice\ZUGFeRD;

use Codeception\PHPUnit\TestCase;
use FreshAdvance\Invoice\DataType\InvoiceDataInterface;
use FreshAdvance\Invoice\ElectronicInvoice\ZUGFeRD\InvoiceGeneratorDecorator;
use FreshAdvance\Invoice\ElectronicInvoice\ZUGFeRD\Service\ZugferdPdfMergerInterface;
use FreshAdvance\Invoice\ElectronicInvoice\ZUGFeRD\Service\ZugferdXmlBuilderInterface;
use FreshAdvance\Invoice\Pdf\InvoiceGeneratorInterface;
use PHPUnit\Framework\Attributes\Test;

class InvoiceGeneratorDecoratorTest extends TestCase
{
    #[Test]
    public function buildXmlAndMergeItWithPdf(): void
    {
        $input = $this->createStub(InvoiceDataInterface::class);

        $originalGeneratorSpy = $this->createMock(InvoiceGeneratorInterface::class);
        $originalGeneratorSpy->expects($this->once())
            ->method('generate')
            ->with($input)
            ->willReturn($originalGeneratorResult = uniqid());

        $xmlBuilderMock = $this->createMock(ZugferdXmlBuilderInterface::class);
        $xmlBuilderMock->method('getXml')
            ->with($input)
            ->willReturn($xml = uniqid());

        $pdfMergerSpy = $this->createMock(ZugferdPdfMergerInterface::class);
        $pdfMergerSpy->expects($this->once())
            ->method('mergeXmlToPdf')
            ->with($xml, $originalGeneratorResult);

        $sut = new InvoiceGeneratorDecorator(
            originalInvoiceGenerator: $originalGeneratorSpy,
            zugferdXmlBuilder: $xmlBuilderMock,
            zugferdPdfMerger: $pdfMergerSpy,
        );

        $result = $sut->generate($input);
        $this->assertSame($originalGeneratorResult, $result);
    }
}
