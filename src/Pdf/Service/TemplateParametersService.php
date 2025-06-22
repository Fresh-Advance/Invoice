<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Pdf\Service;

use FreshAdvance\Invoice\InvoiceData\DataType\InvoiceDataInterface;
use FreshAdvance\Invoice\Language\Service\NumberWordingServiceInterface;
use FreshAdvance\Invoice\Pdf\Settings\DocumentLayoutSettingsInterface;
use OxidEsales\Eshop\Core\Config;

class TemplateParametersService implements TemplateParametersServiceInterface
{
    public function __construct(
        private readonly NumberWordingServiceInterface $numberWordingService,
        private readonly DocumentLayoutSettingsInterface $documentLayoutSettings,
        private readonly Config $shopConfig,
        private readonly FormatCalculatorInterface $formatCalculator,
    ) {
    }

    public function calculateTemplateParameters(InvoiceDataInterface $invoiceData): array
    {
        $configuration = $invoiceData->getInvoiceConfiguration();
        $formattedInvoiceNumber = $this->formatCalculator->calculateByFormat(
            $configuration->getNumber(),
            $invoiceData
        );

        return [
            'invoice' => $invoiceData,
            'wording' => $this->numberWordingService,
            'layoutSettings' => $this->documentLayoutSettings,
            'shopConfig' => $this->shopConfig,
            'invoiceNumber' => $formattedInvoiceNumber,
        ];
    }
}
