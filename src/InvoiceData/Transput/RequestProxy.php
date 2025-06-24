<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\InvoiceData\Transput;

use FreshAdvance\Invoice\InvoiceData\InvoiceConfiguration\DataType\InvoiceConfiguration;
use FreshAdvance\Invoice\InvoiceData\InvoiceConfiguration\DataType\InvoiceConfigurationInterface;
use OxidEsales\Eshop\Core\Request;

class RequestProxy implements RequestInterface
{
    public const REQUEST_PARAM_INVOICE_DATA = 'invoice';
    public const REQUEST_PARAM_ORDER_ID = 'oxid';

    public function __construct(
        readonly private Request $request
    ) {
    }

    public function getInvoiceIdFromRequest(): string
    {
        /** @var string|null $value */
        $value = $this->request->getRequestParameter(self::REQUEST_PARAM_ORDER_ID);
        return (string)$value;
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
