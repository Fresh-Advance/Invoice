<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\Service;

use FreshAdvance\Invoice\DataType\PdfData;
use FreshAdvance\Invoice\Service\PdfGenerator;
use Mpdf\Mpdf;
use PHPUnit\Framework\TestCase;

class PdfGeneratorTest extends TestCase
{
    public function testGetBinaryPdfFromData(): void
    {
        $pdfProcessorMock = $this->createPartialMock(
            Mpdf::class,
            ['SetHTMLHeader', 'WriteHTML', 'SetHTMLFooter', 'OutputBinaryData']
        );
        $pdfProcessorMock->expects($this->once())->method('SetHTMLHeader')->with('someHeaderHtml');
        $pdfProcessorMock->expects($this->once())->method('SetHTMLFooter')->with('someFooterHtml');
        $pdfProcessorMock->expects($this->once())->method('WriteHTML')->with('someContentHtml');
        $pdfProcessorMock->method('OutputBinaryData')->willReturn('someGenerationResult');

        $sut = new PdfGenerator($pdfProcessorMock);

        $data = new PdfData(
            htmlContent: 'someContentHtml',
            htmlHeader: 'someHeaderHtml',
            htmlFooter: 'someFooterHtml'
        );

        $this->assertSame('someGenerationResult', $sut->getBinaryPdfFromData($data));
    }
}
