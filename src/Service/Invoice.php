<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Service;

use FreshAdvance\Invoice\DataType\InvoiceConfiguration;
use FreshAdvance\Invoice\DataType\InvoiceConfigurationInterface;
use FreshAdvance\Invoice\DataType\InvoiceData;
use FreshAdvance\Invoice\DataType\InvoiceDataInterface;
use FreshAdvance\Invoice\Repository\Invoice as InvoiceRepository;
use OxidEsales\Eshop\Application\Model\Order as OrderModel;
use OxidEsales\Eshop\Core\Config;
use Symfony\Component\Filesystem\Path;

class Invoice
{
    public function __construct(
        protected Order $orderService,
        protected Shop $shopService,
        protected Config $shopConfig,
        protected ContextInterface $moduleContext,
        protected InvoiceRepository $invoiceRepository
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
            invoiceConfiguration: $this->invoiceRepository->getOrderInvoice($orderId),
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

    public function saveOrderInvoiceData(InvoiceConfigurationInterface $configuration): void
    {
        $this->invoiceRepository->saveInvoiceConfiguration($configuration);
    }
}
