<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Integration\Document\MpdfDocument;

use FreshAdvance\Invoice\DataType\InvoiceData;
use FreshAdvance\Invoice\DataType\InvoiceDataInterface;
use FreshAdvance\Invoice\Document\MpdfDocument\Builder;
use FreshAdvance\Invoice\Language\Service\LanguageProxy;
use FreshAdvance\Invoice\Language\Service\NumberWordingServiceInterface;
use FreshAdvance\Invoice\Service\OrderServiceInterface;
use FreshAdvance\Invoice\Settings\Service\DocumentLayoutSettingsServiceInterface;
use Mpdf\Mpdf;
use org\bovigo\vfs\vfsStream;
use OxidEsales\Eshop\Application\Model\Order;
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

        $sut = $this->getSut(
            pdfProcessor: $pdfProcessorMock,
            templateRenderer: $templateRenderer,
            shopLanguage: $shopLanguage,
            layoutSettingsService: $layoutSettingsServiceStub,
            numberWordingService: $numberWordingServiceStub,
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

    public function testGenerateTriggersOrderNumberingUpdate(): void
    {
        $invoiceData = $this->createConfiguredMock(InvoiceDataInterface::class, [
            'getOrder' => $this->createStub(Order::class)
        ]);

        $sut = $this->getSut(
            orderService: $orderServiceSpy = $this->createMock(OrderServiceInterface::class),
        );

        $orderServiceSpy->expects($this->once())
            ->method('prepareOrderInvoiceNumber')
            ->with($invoiceData);

        $sut->generate($invoiceData);
    }

    public function getSut(
        Mpdf $pdfProcessor = null,
        TemplateRendererInterface $templateRenderer = null,
        LanguageProxy $shopLanguage = null,
        DocumentLayoutSettingsServiceInterface $layoutSettingsService = null,
        NumberWordingServiceInterface $numberWordingService = null,
        OrderServiceInterface $orderService = null,
    ): Builder {
        return new Builder(
            pdfProcessor: $pdfProcessor ?? $this->createStub(Mpdf::class),
            templateRenderer: $templateRenderer ?? $this->createStub(TemplateRendererInterface::class),
            shopLanguage: $shopLanguage ?? $this->createStub(LanguageProxy::class),
            layoutSettingsService: $layoutSettingsService ?? $this->createStub(DocumentLayoutSettingsServiceInterface::class),
            numberWordingService: $numberWordingService ?? $this->createStub(NumberWordingServiceInterface::class),
            orderService: $orderService ?? $this->createStub(OrderServiceInterface::class),
        );
    }
}
