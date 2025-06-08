<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Pdf\Service;

use FreshAdvance\Invoice\DataType\InvoiceDataInterface;
use FreshAdvance\Invoice\Pdf\Settings\DocumentLayoutSettingsInterface;
use FreshAdvance\Invoice\Language\Service\NumberWordingServiceInterface;
use OxidEsales\Eshop\Core\Config;

class TemplateParametersService implements TemplateParametersServiceInterface
{
    public function __construct(
        private readonly NumberWordingServiceInterface $numberWordingService,
        private readonly DocumentLayoutSettingsInterface $documentLayoutSettings,
        private readonly Config $shopConfig,
    ) {
    }

    public function calculateTemplateParameters(InvoiceDataInterface $invoiceData): array
    {
        return [
            'invoice' => $invoiceData,
            'wording' => $this->numberWordingService,
            'layoutSettings' => $this->documentLayoutSettings,
            'shopConfig' => $this->shopConfig,
        ];
    }
}
