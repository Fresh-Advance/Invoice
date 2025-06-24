<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\InvoiceData\Transput;

use FreshAdvance\Invoice\InvoiceData\InvoiceConfiguration\DataType\InvoiceConfigurationInterface;

interface RequestInterface
{
    public function getInvoiceIdFromRequest(): string;

    public function getInvoiceConfigurationFromRequest(): InvoiceConfigurationInterface;
}
