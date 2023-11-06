<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Integration\Document\MpdfDocument;

use FreshAdvance\Invoice\DataType\InvoiceData;
use FreshAdvance\Invoice\Document\MpdfDocument\Builder;
use FreshAdvance\Invoice\Service\NumberWordingServiceInterface;
use FreshAdvance\Invoice\Settings\ModuleSettings;
use FreshAdvance\Invoice\Transition\Core\Language;
use FreshAdvance\Invoice\Transition\Core\LanguageProxy;
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
        $tempDirectory = vfsStream::setup('root');
        $virtualFilePath = $tempDirectory->url() . '/somePath/someFilename.pdf';

        $pdfProcessorMock = $this->createPartialMock(
            Mpdf::class,
            ['SetHTMLHeader', 'WriteHTML', 'SetHTMLFooter', 'OutputFile']
        );
        $pdfProcessorMock->expects($this->once())->method('WriteHTML')->with('someContentHtml');
        $pdfProcessorMock->expects($this->once())->method('SetHtmlFooter')->with('someFooter');
        $pdfProcessorMock->expects($this->once())->method('OutputFile')->with($virtualFilePath);

        $templateRenderer = $this->createConfiguredMock(TemplateRendererInterface::class, [
            'renderTemplate' => 'someContentHtml'
        ]);

        $shopLanguage = $this->createPartialMock(LanguageProxy::class, ['getTplLanguage', 'forceSetTplLanguage']);
        $shopLanguage->expects($this->exactly(2))->method('forceSetTplLanguage');

        $moduleSettings = $this->createConfiguredMock(ModuleSettings::class, [
            'getDocumentFooter' => 'someFooter'
        ]);

        $sut = new Builder(
            pdfProcessor: $pdfProcessorMock,
            templateRenderer: $templateRenderer,
            shopLanguage: $shopLanguage,
            moduleSettings: $moduleSettings,
            numberWordingService: $this->createStub(NumberWordingServiceInterface::class)
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
