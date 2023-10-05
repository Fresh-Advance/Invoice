<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Transput;

use FreshAdvance\Invoice\DataType\InvoiceConfiguration;
use FreshAdvance\Invoice\DataType\InvoiceConfigurationInterface;
use OxidEsales\Eshop\Core\Request;

class RequestProxy implements RequestInterface
{
    public const INVOICES_FORM_ARRAY = 'invoice';

    public function __construct(
        private Request $request
    ) {
    }

    public function getRequestEscapedParameter(string $requestParam): mixed
    {
        return $this->request->getRequestEscapedParameter($requestParam);
    }

    public function getRequestParameter(string $requestParam, string $defaultValue = null): mixed
    {
        return $this->request->getRequestParameter($requestParam, $defaultValue);
    }

    public function getInvoiceConfigurationFromRequest(): InvoiceConfigurationInterface
    {
        /** @var array<string,null|string> $formData */
        $formData = $this->request->getRequestParameter(self::INVOICES_FORM_ARRAY);
        return new InvoiceConfiguration(
            orderId: (string)$formData['order_id'],
            signer: (string)$formData['signer'],
            date: (string)$formData['date'],
            number: (string)$formData['number']
        );
    }
}
