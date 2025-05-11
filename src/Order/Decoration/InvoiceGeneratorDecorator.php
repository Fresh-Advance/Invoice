<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Order\Decoration;

use FreshAdvance\Invoice\DataType\InvoiceDataInterface;
use FreshAdvance\Invoice\Pdf\InvoiceGeneratorInterface;
use FreshAdvance\Invoice\Order\Service\OrderServiceInterface;
use FreshAdvance\Invoice\Order\Settings\OrderSettingsInterface;

class InvoiceGeneratorDecorator implements InvoiceGeneratorInterface
{
    public function __construct(
        readonly private InvoiceGeneratorInterface $originalGenerator,
        readonly private OrderServiceInterface $orderService,
        readonly private OrderSettingsInterface $orderSettings,
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
