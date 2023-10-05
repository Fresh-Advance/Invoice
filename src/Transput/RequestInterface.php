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
    public function getRequestEscapedParameter(string $requestParam): mixed;

    public function getRequestParameter(string $requestParam, string $defaultValue = null): mixed;

    public function getInvoiceConfigurationFromRequest(): InvoiceConfigurationInterface;
}
