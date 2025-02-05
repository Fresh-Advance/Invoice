<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Integration\Document\MpdfDocument;

use FreshAdvance\Invoice\DataType\InvoiceData;
use FreshAdvance\Invoice\Document\MpdfDocument\Builder;
use FreshAdvance\Invoice\Document\Service\DocumentRendererInterface;
use FreshAdvance\Invoice\Document\Service\TemplateParametersServiceInterface;
use FreshAdvance\Invoice\Language\Service\LanguageProxy;
use Mpdf\Mpdf;
use org\bovigo\vfs\vfsStream;
use OxidEsales\EshopCommunity\Internal\Framework\Templating\TemplateRendererInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FreshAdvance\Invoice\Document\MpdfDocument\Builder
 */
class BuilderTest extends TestCase
{
    public function testGetBinaryPdfFromData(): void
    {
        $tempDirectory = vfsStream::setup();
        $virtualFilePath = $tempDirectory->url() . '/somePath/someFilename.pdf';

        $pdfProcessorMock = $this->createPartialMock(Mpdf::class, ['WriteHTML', 'OutputFile']);
        $pdfProcessorMock->expects($this->once())->method('WriteHTML')->with('someContentHtml');
        $pdfProcessorMock->expects($this->once())->method('OutputFile')->with($virtualFilePath);

        $invoiceData = $this->createConfiguredMock(InvoiceData::class, [
            'getInvoicePath' => $virtualFilePath
        ]);

        $documentRenderer = $this->createMock(DocumentRendererInterface::class);
        $documentRenderer->method('render')
            ->with($invoiceData)
            ->willReturn('someContentHtml');

        $sut = $this->getSut(
            pdfProcessor: $pdfProcessorMock,
            documentRenderer: $documentRenderer,
        );

        $tempDirectory = vfsStream::setup();
        $this->assertDirectoryDoesNotExist($tempDirectory->url() . '/somePath/');

        $sut->generate($invoiceData);
        $this->assertDirectoryExists($tempDirectory->url() . '/somePath/');
    }

    public function getSut(
        Mpdf $pdfProcessor = null,
        DocumentRendererInterface $documentRenderer = null
    ): Builder {
        return new Builder(
            pdfProcessor: $pdfProcessor ?? $this->createStub(Mpdf::class),
            documentRenderer: $documentRenderer ?? $this->createStub(DocumentRendererInterface::class),
        );
    }
}
