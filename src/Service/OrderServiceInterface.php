<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

namespace FreshAdvance\Invoice\Service;

use FreshAdvance\Invoice\DataType\InvoiceDataInterface;

interface OrderServiceInterface
{
    public function prepareOrderInvoiceNumber(InvoiceDataInterface $invoiceData): void;
}
