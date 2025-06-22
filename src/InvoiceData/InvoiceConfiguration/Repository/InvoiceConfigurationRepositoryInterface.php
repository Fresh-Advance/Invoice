<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\InvoiceData\InvoiceConfiguration\Repository;

use FreshAdvance\Invoice\InvoiceData\InvoiceConfiguration\DataType\InvoiceConfigurationInterface;
use FreshAdvance\Invoice\InvoiceData\InvoiceConfiguration\Exception\InvoiceConfigurationNotFound;

interface InvoiceConfigurationRepositoryInterface
{
    /**
     * @throws InvoiceConfigurationNotFound
     */
    public function getByOrderId(string $orderId): InvoiceConfigurationInterface;

    public function save(InvoiceConfigurationInterface $invoiceConfiguration): void;
}
