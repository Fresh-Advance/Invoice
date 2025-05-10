<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Service;

use FreshAdvance\Invoice\DataType\InvoiceConfigurationInterface;
use FreshAdvance\Invoice\DataType\InvoiceData;
use FreshAdvance\Invoice\DataType\InvoiceDataInterface;
use FreshAdvance\Invoice\Document\Service\FilenameCalculatorInterface;
use FreshAdvance\Invoice\Order\Repository\OrderRepositoryInterface;
use FreshAdvance\Invoice\Repository\InvoiceConfigurationRepositoryInterface;
use FreshAdvance\Invoice\Repository\ShopRepositoryInterface;
use FreshAdvance\Invoice\Settings\ConfigInterface;
use FreshAdvance\Invoice\Settings\ContextInterface;
use FreshAdvance\Invoice\Settings\ModuleSettingsInterface;
use OxidEsales\Eshop\Application\Model\Order as OrderModel;
use Symfony\Component\Filesystem\Path;

/**
 * @todo: split this class into smaller classes
 * @SuppressWarnings(PHPMD)
 */
class Invoice
{
    public function __construct(
        protected OrderRepositoryInterface $orderRepository,
        protected ShopRepositoryInterface $shopService,
        protected ConfigInterface $shopConfig,
        protected ContextInterface $moduleContext,
        protected InvoiceConfigurationRepositoryInterface $invoiceConfigRepo,
        protected ModuleSettingsInterface $moduleSettings,
        protected FilenameCalculatorInterface $filenameCalculator,
    ) {
    }

    public function getInvoiceDataByOrderId(string $orderId): InvoiceDataInterface
    {
        $order = $this->orderRepository->getByOrderId($orderId);
        $configuration = $this->invoiceConfigRepo->getByOrderId($orderId);

        return new InvoiceData(
            order: $order,
            shop: $this->shopService->getByShopId($order->getShopId()),
            invoicePath: $this->getOrderInvoicePath($order),
            invoiceConfiguration: $configuration,
            languageId: $this->shopConfig->getShopDefaultLanguageId($order->getShopId())
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

    public function getInvoiceFileName(InvoiceDataInterface $invoiceData): string
    {
        return $this->filenameCalculator->calculateByFormat(
            $this->moduleSettings->getFileNameFormat(),
            $invoiceData
        );
    }

    public function saveOrderInvoiceData(InvoiceConfigurationInterface $configuration): void
    {
        $this->invoiceConfigRepo->save($configuration);
    }
}
