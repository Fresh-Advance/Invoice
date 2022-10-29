<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\DataType;

use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Application\Model\Shop;

class InvoiceData implements InvoiceDataInterface
{
    public function __construct(
        protected Order $order,
        protected Shop $shop,
        protected string $invoicePath,
        protected int $languageId = 0,
    ) {
    }

    public function getOrder(): Order
    {
        return $this->order;
    }

    public function getShop(): Shop
    {
        return $this->shop;
    }

    public function getLanguageId(): int
    {
        return $this->languageId;
    }

    public function getInvoicePath(): string
    {
        return $this->invoicePath;
    }
}
