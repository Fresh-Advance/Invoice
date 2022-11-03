<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\Service;

use FreshAdvance\Invoice\DataType\InvoiceData;
use FreshAdvance\Invoice\Service\PdfGenerator;
use Mpdf\Mpdf;
use org\bovigo\vfs\vfsStream;
use OxidEsales\EshopCommunity\Internal\Framework\Templating\TemplateRendererInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FreshAdvance\Invoice\Service\PdfGenerator
 */
class PdfGeneratorTest extends TestCase
{
    public function testGetBinaryPdfFromData(): void
    {
        $tempDirectory = vfsStream::setup('root');
        $virtualFilePath = $tempDirectory->url() . '/somePath/someFilename.pdf';

        $pdfProcessorMock = $this->createPartialMock(
            Mpdf::class,
            ['SetHTMLHeader', 'WriteHTML', 'SetHTMLFooter', 'OutputFile']
        );
        $pdfProcessorMock->expects($this->once())->method('WriteHTML')->with('someContentHtml');
        $pdfProcessorMock->expects($this->once())->method('OutputFile')->with($virtualFilePath);

        $templateRenderer = $this->createConfiguredMock(TemplateRendererInterface::class, [
            'renderTemplate' => 'someContentHtml'
        ]);

        $sut = new PdfGenerator(
            $pdfProcessorMock,
            $templateRenderer
        );

        $invoiceData = $this->createConfiguredMock(InvoiceData::class, [
            'getInvoicePath' => $virtualFilePath
        ]);

        $tempDirectory = vfsStream::setup('root');
        $this->assertDirectoryDoesNotExist($tempDirectory->url() . '/somePath/');

        $sut->generate($invoiceData);
        $this->assertDirectoryExists($tempDirectory->url() . '/somePath/');
    }
}
