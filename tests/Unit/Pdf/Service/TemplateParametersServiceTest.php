<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\Pdf\Service;

use FreshAdvance\Invoice\InvoiceData\DataType\InvoiceDataInterface;
use FreshAdvance\Invoice\InvoiceData\InvoiceConfiguration\DataType\InvoiceConfigurationInterface;
use FreshAdvance\Invoice\Language\Service\NumberWordingServiceInterface;
use FreshAdvance\Invoice\Pdf\Service\FormatCalculatorInterface;
use FreshAdvance\Invoice\Pdf\Service\TemplateParametersService;
use FreshAdvance\Invoice\Pdf\Service\TemplateParametersServiceInterface;
use FreshAdvance\Invoice\Pdf\Settings\DocumentLayoutSettingsInterface;
use OxidEsales\Eshop\Core\Config;
use PHPUnit\Framework\TestCase;

class TemplateParametersServiceTest extends TestCase
{
    public function testParametersListCreated(): void
    {
        $invoiceDataStub = $this->createConfiguredStub(InvoiceDataInterface::class, [
            'getInvoiceConfiguration' => $this->createConfiguredStub(InvoiceConfigurationInterface::class, [
                'getNumber' => $numberFormat = uniqid(),
            ]),
        ]);

        $formatCalculatorMock = $this->createMock(FormatCalculatorInterface::class);
        $formatCalculatorMock->expects($this->any())
            ->method('calculateByFormat')
            ->with($numberFormat, $invoiceDataStub)
            ->willReturn($formattedInvoiceNumber = uniqid());

        $sut = $this->getSut(
            numberWordingService: $wordingServiceStub = $this->createStub(NumberWordingServiceInterface::class),
            layoutSettings: $layoutSettingsStub = $this->createStub(DocumentLayoutSettingsInterface::class),
            shopConfig: $shopConfigStub = $this->createStub(Config::class),
            formatCalculator: $formatCalculatorMock,
        );

        $result = $sut->calculateTemplateParameters($invoiceDataStub);

        $this->assertEquals([
            'invoice' => $invoiceDataStub,
            'wording' => $wordingServiceStub,
            'layoutSettings' => $layoutSettingsStub,
            'shopConfig' => $shopConfigStub,
            'invoiceNumber' => $formattedInvoiceNumber,
        ], $result);
    }

    private function getSut(
        ?NumberWordingServiceInterface $numberWordingService = null,
        ?DocumentLayoutSettingsInterface $layoutSettings = null,
        ?Config $shopConfig = null,
        ?FormatCalculatorInterface $formatCalculator = null,
    ): TemplateParametersServiceInterface {
        return new TemplateParametersService(
            numberWordingService: $numberWordingService ?? $this->createStub(NumberWordingServiceInterface::class),
            documentLayoutSettings: $layoutSettings ?? $this->createStub(DocumentLayoutSettingsInterface::class),
            shopConfig: $shopConfig ?? $this->createStub(Config::class),
            formatCalculator: $formatCalculator ?? $this->createStub(FormatCalculatorInterface::class),
        );
    }
}
