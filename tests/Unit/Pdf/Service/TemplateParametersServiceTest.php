<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\Pdf\Service;

use FreshAdvance\Invoice\DataType\InvoiceDataInterface;
use FreshAdvance\Invoice\Pdf\Service\TemplateParametersService;
use FreshAdvance\Invoice\Pdf\Service\TemplateParametersServiceInterface;
use FreshAdvance\Invoice\Pdf\Settings\DocumentLayoutSettingsInterface;
use FreshAdvance\Invoice\Language\Service\NumberWordingServiceInterface;
use OxidEsales\Eshop\Core\Config;
use PHPUnit\Framework\TestCase;

class TemplateParametersServiceTest extends TestCase
{
    public function testParametersListCreated(): void
    {
        $sut = $this->getSut(
            numberWordingService: $wordingServiceStub = $this->createStub(NumberWordingServiceInterface::class),
            layoutSettings: $layoutSettingsStub = $this->createStub(DocumentLayoutSettingsInterface::class),
            shopConfig: $shopConfigStub = $this->createStub(Config::class),
        );

        $invoiceDataStub = $this->createStub(InvoiceDataInterface::class);
        $result = $sut->calculateTemplateParameters($invoiceDataStub);

        $this->assertSame([
            'invoice' => $invoiceDataStub,
            'wording' => $wordingServiceStub,
            'layoutSettings' => $layoutSettingsStub,
            'shopConfig' => $shopConfigStub,
        ], $result);
    }

    private function getSut(
        ?NumberWordingServiceInterface $numberWordingService = null,
        ?DocumentLayoutSettingsInterface $layoutSettings = null,
        ?Config $shopConfig = null,
    ): TemplateParametersServiceInterface {
        return new TemplateParametersService(
            numberWordingService: $numberWordingService ?? $this->createStub(NumberWordingServiceInterface::class),
            documentLayoutSettings: $layoutSettings ?? $this->createStub(DocumentLayoutSettingsInterface::class),
            shopConfig: $shopConfig ?? $this->createStub(Config::class),
        );
    }
}
