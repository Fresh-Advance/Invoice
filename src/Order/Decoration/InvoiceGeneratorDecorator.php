<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Order\Decoration;

use FreshAdvance\Invoice\DataType\InvoiceDataInterface;
use FreshAdvance\Invoice\Document\InvoiceGeneratorInterface;
use FreshAdvance\Invoice\Order\Settings\OrderSettingsInterface;
use FreshAdvance\Invoice\Service\OrderServiceInterface;

class InvoiceGeneratorDecorator implements InvoiceGeneratorInterface
{
    public function __construct(
        private InvoiceGeneratorInterface $originalGenerator,
        private OrderServiceInterface $orderService,
        private OrderSettingsInterface $orderSettings,
    ) {
    }

    public function generate(InvoiceDataInterface $invoiceData): void
    {
        if ($this->orderSettings->isOrderInvoiceNumberUpdateActive()) {
            $this->orderService->prepareOrderInvoiceNumber($invoiceData);
        }

        $this->originalGenerator->generate($invoiceData);
    }
}
