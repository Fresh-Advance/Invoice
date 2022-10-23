<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Service;

use FreshAdvance\Invoice\DataType\InvoiceData;
use FreshAdvance\Invoice\DataType\InvoiceDataInterface;

class Invoice
{
    public function __construct(
        protected Order $orderService,
        protected Shop $shopService
    ) {
    }

    public function getInvoiceData(): InvoiceDataInterface
    {
        return new InvoiceData(
            order: $this->orderService->getRequestOrder(),
            shop: $this->shopService->getActiveShop()
        );
    }
}
