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
use FreshAdvance\Invoice\Repository\Shop;
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
        protected InvoiceRepository $invoiceRepository,
        protected ModuleSettingsInterface $moduleSettings
    ) {
    }

    public function getInvoiceDataByOrderId(string $orderId): InvoiceDataInterface
    {
        $order = $this->orderService->getOrder($orderId);

        /** @var string $orderShopLanguage */
        $orderShopLanguage = $this->shopConfig->getShopConfVar('sDefaultLang', $order->getShopId());
        $configuration = $this->invoiceRepository->getOrderInvoice($orderId)
            ?? new InvoiceConfiguration(orderId: $orderId);

        return new InvoiceData(
            order: $order,
            shop: $this->shopService->getShop($order->getShopId()),
            invoicePath: $this->getOrderInvoicePath($order),
            invoiceConfiguration: $configuration,
            languageId: (int)$orderShopLanguage
        );
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function getOrderInvoicePath(OrderModel $order): string
    {
        return Path::join(
            $this->moduleContext->getInvoicesPath(),
            substr($order->getId(), 0, 2),
            $order->getId() . '.pdf'
        );
    }

    public function getInvoiceFileName(InvoiceConfigurationInterface $configuration): string
    {
        return $this->moduleSettings->getFilePrefix()
            . $configuration->getNumber()
            . '.pdf';
    }

    public function saveOrderInvoiceData(InvoiceConfigurationInterface $configuration): void
    {
        $this->invoiceRepository->saveInvoiceConfiguration($configuration);
    }
}
