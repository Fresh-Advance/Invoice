<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Service;

use FreshAdvance\Invoice\DataType\InvoiceData;
use FreshAdvance\Invoice\DataType\InvoiceDataInterface;
use OxidEsales\Eshop\Application\Model\Order as OrderModel;
use OxidEsales\Eshop\Core\Config;
use Webmozart\PathUtil\Path;

class Invoice
{
    public function __construct(
        protected Order $orderService,
        protected Shop $shopService,
        protected Config $shopConfig,
        protected ContextInterface $moduleContext
    ) {
    }

    public function getInvoiceDataByOrderId(string $orderId): InvoiceDataInterface
    {
        $order = $this->orderService->getOrder($orderId);

        /** @var string $orderShopLanguage */
        $orderShopLanguage = $this->shopConfig->getShopConfVar('sDefaultLang', $order->getShopId());

        return new InvoiceData(
            order: $order,
            shop: $this->shopService->getShop($order->getShopId()),
            invoicePath: $this->getOrderInvoicePath($order),
            languageId: (int)$orderShopLanguage
        );
    }

    public function getOrderInvoicePath(OrderModel $order): string
    {
        return Path::join(
            $this->moduleContext->getInvoicesPath(),
            substr($order->getId(), 0, 2),
            $order->getId() . '.pdf'
        );
    }
}
