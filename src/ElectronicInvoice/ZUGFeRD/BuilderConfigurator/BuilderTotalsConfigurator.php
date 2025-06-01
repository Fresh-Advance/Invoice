<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\ElectronicInvoice\ZUGFeRD\BuilderConfigurator;

use FreshAdvance\Invoice\DataType\InvoiceDataInterface;
use horstoeko\zugferd\ZugferdDocumentBuilder;
use OxidEsales\Eshop\Application\Model\Order;

class BuilderTotalsConfigurator implements BuilderConfiguratorInterface
{
    public function configureBuilder(
        ZugferdDocumentBuilder $builder,
        InvoiceDataInterface $invoiceData
    ): ZugferdDocumentBuilder {
        $order = $invoiceData->getOrder();

        $builder->setDocumentSummation(
            grandTotalAmount: (float)$order->getFieldData('OXTOTALORDERSUM'),
            duePayableAmount: 0,
            lineTotalAmount: $this->getOrderItemsNet($order),
            chargeTotalAmount: $this->getOrderSurcharges($order),
            allowanceTotalAmount: $this->getOrderDiscounts($order),
            taxBasisTotalAmount: $this->getOrderTotalNet($order),
            taxTotalAmount: $this->getOrderTotalTax($order),
            totalPrepaidAmount: (float)$order->getFieldData('OXTOTALORDERSUM'),
        );

        return $builder;
    }

    private function getOrderItemsNet(Order $order): float
    {
        $itemsNet = $order->getFieldData('OXTOTALNETSUM');

        return (float)$itemsNet;
    }

    private function getOrderSurcharges(Order $order): float
    {
        $surcharges = $order->getOrderDeliveryPrice()->getNettoPrice()
            + $order->getOrderPaymentPrice()->getNettoPrice()
            + $order->getOrderWrappingPrice()->getNettoPrice();

        return (float)$surcharges;
    }

    private function getOrderDiscounts(Order $order): float
    {
        $discounts = (float)$order->getFieldData('OXDISCOUNT')
            + (float)$order->getFieldData('OXVOUCHERDISCOUNT');

        return $discounts;
    }

    private function getOrderTotalNet(Order $order): float
    {
        $netTotal = (float)$order->getFieldData('OXTOTALNETSUM')
            + $order->getOrderDeliveryPrice()->getNettoPrice()
            + $order->getOrderPaymentPrice()->getNettoPrice()
            + $order->getOrderWrappingPrice()->getNettoPrice();

        return $netTotal;
    }

    private function getOrderTotalTax(Order $order): float
    {
        $taxes = (float)$order->getFieldData('OXTOTALBRUTSUM')
            - (float)$order->getFieldData('OXTOTALNETSUM')
            + $order->getOrderDeliveryPrice()->getVatValue()
            + $order->getOrderPaymentPrice()->getVatValue()
            + $order->getOrderWrappingPrice()->getVatValue();

        return $taxes;
    }
}
