<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Integration\Document\MpdfDocument;

use FreshAdvance\Invoice\DataType\InvoiceData;
use FreshAdvance\Invoice\Document\MpdfDocument\Builder;
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

        $shopLanguage = $this->createPartialMock(LanguageProxy::class, ['getTplLanguage', 'forceSetTplLanguage']);
        $shopLanguage->expects($this->exactly(2))->method('forceSetTplLanguage');

        $invoiceData = $this->createConfiguredMock(InvoiceData::class, [
            'getInvoicePath' => $virtualFilePath
        ]);

        $templateParametersService = $this->createMock(TemplateParametersServiceInterface::class);
        $templateParametersService->method('calculateTemplateParameters')
            ->with($invoiceData)
            ->willReturn($preparedParams = [uniqid() => uniqid()]);

        $templateRenderer = $this->createMock(TemplateRendererInterface::class);

        $sut = $this->getSut(
            pdfProcessor: $pdfProcessorMock,
            templateRenderer: $templateRenderer,
            shopLanguage: $shopLanguage,
            templateParametersService: $templateParametersService,
        );

        $templateRenderer->expects($this->once())->method('renderTemplate')
            ->with(Builder::INVOICE_TEMPLATE, $preparedParams)
            ->willReturn('someContentHtml');

        $tempDirectory = vfsStream::setup();
        $this->assertDirectoryDoesNotExist($tempDirectory->url() . '/somePath/');

        $sut->generate($invoiceData);
        $this->assertDirectoryExists($tempDirectory->url() . '/somePath/');
    }

    public function getSut(
        Mpdf $pdfProcessor = null,
        TemplateRendererInterface $templateRenderer = null,
        LanguageProxy $shopLanguage = null,
        TemplateParametersServiceInterface $templateParametersService = null,
    ): Builder {
        $templateParametersService ??= $this->createStub(TemplateParametersServiceInterface::class);

        return new Builder(
            pdfProcessor: $pdfProcessor ?? $this->createStub(Mpdf::class),
            templateRenderer: $templateRenderer ?? $this->createStub(TemplateRendererInterface::class),
            shopLanguage: $shopLanguage ?? $this->createStub(LanguageProxy::class),
            templateParametersService: $templateParametersService,
        );
    }
}
