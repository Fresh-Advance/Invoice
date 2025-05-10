<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Repository;

use FreshAdvance\Invoice\DataType\InvoiceConfigurationInterface;
use FreshAdvance\Invoice\Exception\InvoiceConfigurationNotFound;

interface InvoiceConfigurationRepositoryInterface
{
    /**
     * @throws InvoiceConfigurationNotFound
     */
    public function getByOrderId(string $orderId): InvoiceConfigurationInterface;

    public function save(InvoiceConfigurationInterface $invoiceConfiguration): void;
}
