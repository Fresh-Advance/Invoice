<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\InvoiceData\InvoiceConfiguration\Repository;

use FreshAdvance\Invoice\InvoiceData\InvoiceConfiguration\DataType\InvoiceConfiguration;
use FreshAdvance\Invoice\InvoiceData\InvoiceConfiguration\DataType\InvoiceConfigurationInterface;
use FreshAdvance\Invoice\InvoiceData\InvoiceConfiguration\Exception\InvoiceConfigurationNotFound;
use FreshAdvance\Invoice\Settings\ModuleSettingsInterface;

class InvoiceConfigurationRepositoryDecoration implements InvoiceConfigurationRepositoryInterface
{
    public function __construct(
        readonly private InvoiceConfigurationRepositoryInterface $originalRepository,
        readonly private ModuleSettingsInterface $moduleSettings,
    ) {
    }

    public function getByOrderId(string $orderId): InvoiceConfigurationInterface
    {
        try {
            $result = $this->originalRepository->getByOrderId($orderId);
        } catch (InvoiceConfigurationNotFound) {
            $result = new InvoiceConfiguration(
                orderId: $orderId,
                date: $this->moduleSettings->getInvoiceDateFormat(),
                number: $this->moduleSettings->getInvoiceNumberFormat(),
            );
        }

        return $result;
    }

    public function save(InvoiceConfigurationInterface $invoiceConfiguration): void
    {
        $this->originalRepository->save($invoiceConfiguration);
    }
}
