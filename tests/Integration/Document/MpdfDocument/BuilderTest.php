<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Integration\Document\MpdfDocument;

use FreshAdvance\Invoice\DataType\InvoiceData;
use FreshAdvance\Invoice\Document\MpdfDocument\Builder;
use FreshAdvance\Invoice\Language\Service\LanguageProxy;
use FreshAdvance\Invoice\Language\Service\NumberWordingServiceInterface;
use FreshAdvance\Invoice\Settings\Service\DocumentLayoutSettingsServiceInterface;
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

        $shopLanguage = $this->createPartialMock(LanguageProxy::class, ['getTplLanguage', 'forceSetTplLanguage']);
        $shopLanguage->expects($this->exactly(2))->method('forceSetTplLanguage');

        $layoutSettingsServiceStub = $this->createStub(DocumentLayoutSettingsServiceInterface::class);
        $numberWordingServiceStub = $this->createStub(NumberWordingServiceInterface::class);

        $templateRenderer = $this->createMock(TemplateRendererInterface::class);

        $sut = new Builder(
            pdfProcessor: $pdfProcessorMock,
            templateRenderer: $templateRenderer,
            shopLanguage: $shopLanguage,
            layoutSettingsService: $layoutSettingsServiceStub,
            numberWordingService: $numberWordingServiceStub
        );

        $invoiceData = $this->createConfiguredMock(InvoiceData::class, [
            'getInvoicePath' => $virtualFilePath
        ]);

        $templateRenderer->expects($this->once())->method('renderTemplate')
            ->with(
                Builder::INVOICE_TEMPLATE,
                [
                    'invoice' => $invoiceData,
                    'wording' => $numberWordingServiceStub,
                    'layoutSettings' => $layoutSettingsServiceStub,
                ]
            )
            ->willReturn('someContentHtml');

        $tempDirectory = vfsStream::setup();
        $this->assertDirectoryDoesNotExist($tempDirectory->url() . '/somePath/');

        $sut->generate($invoiceData);
        $this->assertDirectoryExists($tempDirectory->url() . '/somePath/');
    }
}
