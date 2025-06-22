<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\Pdf\Service;

use FreshAdvance\Invoice\InvoiceData\DataType\InvoiceDataInterface;
use FreshAdvance\Invoice\Language\Service\LanguageProxy;
use FreshAdvance\Invoice\Pdf\Service\DocumentRenderer;
use FreshAdvance\Invoice\Pdf\Service\TemplateParametersServiceInterface;
use OxidEsales\EshopCommunity\Internal\Framework\Templating\TemplateRendererInterface;
use PHPUnit\Framework\TestCase;

class DocumentRendererTest extends TestCase
{
    public function testParametersListCreated(): void
    {
        $invoiceData = $this->createStub(InvoiceDataInterface::class);

        $templateParametersService = $this->createMock(TemplateParametersServiceInterface::class);
        $templateParametersService->method('calculateTemplateParameters')
            ->with($invoiceData)
            ->willReturn($preparedParams = [uniqid() => uniqid()]);

        $templateRenderer = $this->createMock(TemplateRendererInterface::class);
        $templateRenderer->expects($this->once())->method('renderTemplate')
            ->with(DocumentRenderer::INVOICE_TEMPLATE, $preparedParams)
            ->willReturn($renderedResult = uniqid());

        $shopLanguage = $this->createPartialMock(LanguageProxy::class, ['getTplLanguage', 'forceSetTplLanguage']);
        $shopLanguage->expects($this->exactly(2))->method('forceSetTplLanguage');

        $sut = new DocumentRenderer(
            shopLanguage: $shopLanguage,
            templateRenderer: $templateRenderer,
            templateParametersService: $templateParametersService
        );

        $result = $sut->render($invoiceData);

        $this->assertSame($renderedResult, $result);
    }
}
