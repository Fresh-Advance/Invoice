<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Transput;

use FreshAdvance\Invoice\DataType\InvoiceConfigurationInterface;

interface RequestInterface
{
    public function getInvoiceIdFromRequest(): string;

    public function getInvoiceConfigurationFromRequest(): InvoiceConfigurationInterface;
}
