<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Service;

use FreshAdvance\Invoice\DataType\InvoiceData;
use FreshAdvance\Invoice\DataType\InvoiceDataInterface;
use OxidEsales\Eshop\Core\Config;

class Invoice
{
    public function __construct(
        protected Order $orderService,
        protected Shop $shopService,
        protected Config $shopConfig
    ) {
    }

    public function getInvoiceData(): InvoiceDataInterface
    {
        $order = $this->orderService->getRequestOrder();

        /** @var string $orderShopLanguage */
        $orderShopLanguage = $this->shopConfig->getShopConfVar('sDefaultLang', $order->getShopId());

        return new InvoiceData(
            order: $order,
            shop: $this->shopService->getShop((string)$order->getShopId()), //todo: ensure int result
            languageId: (int)$orderShopLanguage
        );
    }
}
