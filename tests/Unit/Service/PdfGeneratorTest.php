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
use OxidEsales\EshopCommunity\Internal\Framework\Templating\TemplateRendererInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FreshAdvance\Invoice\Service\PdfGenerator
 */
class PdfGeneratorTest extends TestCase
{
    public function testGetBinaryPdfFromData(): void
    {
        $pdfProcessorMock = $this->createPartialMock(
            Mpdf::class,
            ['SetHTMLHeader', 'WriteHTML', 'SetHTMLFooter', 'OutputFile']
        );
        $pdfProcessorMock->expects($this->once())->method('SetHTMLHeader')->with('someHeaderHtml');
        $pdfProcessorMock->expects($this->once())->method('SetHTMLFooter')->with('someFooterHtml');
        $pdfProcessorMock->expects($this->once())->method('WriteHTML')->with('someContentHtml');
        $pdfProcessorMock->expects($this->once())->method('OutputFile')->with('someFilename');

        $sut = new PdfGenerator(
            $pdfProcessorMock,
            $this->createStub(TemplateRendererInterface::class)
        );

        $data = new PdfData(
            htmlContent: 'someContentHtml',
            htmlHeader: 'someHeaderHtml',
            htmlFooter: 'someFooterHtml'
        );

        $sut->generate($data, 'someFilename');
    }
}
