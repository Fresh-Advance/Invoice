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
    public const REQUEST_PARAM_INVOICE_DATA = 'invoice';
    public const REQUEST_PARAM_ORDER_ID = 'orderId';

    public function __construct(
        private Request $request
    ) {
    }

    public function getInvoiceIdFromRequest(): string
    {
        /** @var string|null $formData */
        return (string)$this->request->getRequestParameter(self::REQUEST_PARAM_ORDER_ID);
    }

    public function getInvoiceConfigurationFromRequest(): InvoiceConfigurationInterface
    {
        /** @var array<string,null|string> $formData */
        $formData = $this->request->getRequestParameter(self::REQUEST_PARAM_INVOICE_DATA);
        return new InvoiceConfiguration(
            orderId: (string)$formData['order_id'],
            signer: (string)$formData['signer'],
            date: (string)$formData['date'],
            number: (string)$formData['number']
        );
    }
}
