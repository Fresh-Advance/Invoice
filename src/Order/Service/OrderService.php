<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Order\Service;

use FreshAdvance\Invoice\DataType\InvoiceDataInterface;
use FreshAdvance\Invoice\Repository\OrderRepositoryInterface;

class OrderService implements OrderServiceInterface
{
    public function __construct(
        protected OrderRepositoryInterface $orderRepository
    ) {
    }

    public function prepareOrderInvoiceNumber(InvoiceDataInterface $invoiceData): void
    {
        $this->orderRepository->fillEmptyInvoiceNumber($invoiceData->getOrder());
    }
}
