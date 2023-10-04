<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Service;

use FreshAdvance\Invoice\DataType\InvoiceConfiguration;
use FreshAdvance\Invoice\DataType\InvoiceConfigurationInterface;
use FreshAdvance\Invoice\Transput\RequestInterface;

class RequestDataConverter implements RequestDataConverterInterface
{
    public const INVOICES_FORM_ARRAY = 'invoice';

    public function __construct(
        protected RequestInterface $request
    ) {
    }

    public function getConfigurationFromRequest(): InvoiceConfigurationInterface
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
