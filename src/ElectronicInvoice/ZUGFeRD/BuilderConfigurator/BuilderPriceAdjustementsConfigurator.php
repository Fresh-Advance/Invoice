<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\ElectronicInvoice\ZUGFeRD\BuilderConfigurator;

use FreshAdvance\Invoice\DataType\InvoiceDataInterface;
use horstoeko\zugferd\ZugferdDocumentBuilder;

class BuilderPriceAdjustementsConfigurator implements BuilderConfiguratorInterface
{
    public function configureBuilder(
        ZugferdDocumentBuilder $builder,
        InvoiceDataInterface $invoiceData
    ): ZugferdDocumentBuilder {

        $order = $invoiceData->getOrder();

        $deliveryPrice = $order->getOrderDeliveryPrice();
        $builder->addDocumentAllowanceCharge(
            actualAmount: (float)$deliveryPrice->getNettoPrice(),
            isCharge: true,
            taxCategoryCode: (float)$deliveryPrice->getVat() > 0 ? 'S' : 'Z',
            taxTypeCode: 'VAT',
            rateApplicablePercent: (float)$deliveryPrice->getVat(),
            reason: 'Delivery method surcharge',
        );

        $paymentPrice = $order->getOrderPaymentPrice();
        $builder->addDocumentAllowanceCharge(
            actualAmount: (float)$paymentPrice->getNettoPrice(),
            isCharge: true,
            taxCategoryCode: (float)$paymentPrice->getVat() > 0 ? 'S' : 'Z',
            taxTypeCode: 'VAT',
            rateApplicablePercent: (float)$paymentPrice->getVat(),
            reason: 'Payment method surcharge',
        );

        $wrapPrice = $order->getOrderWrappingPrice();
        $builder->addDocumentAllowanceCharge(
            actualAmount: (float)$wrapPrice->getNettoPrice(),
            isCharge: true,
            taxCategoryCode: (float)$wrapPrice->getVat() > 0 ? 'S' : 'Z',
            taxTypeCode: 'VAT',
            rateApplicablePercent: (float)$wrapPrice->getVat(),
            reason: 'Wrapping surcharge',
        );

        $builder->addDocumentAllowanceCharge(
            actualAmount: (float)$order->getFieldData('OXDISCOUNT'),
            isCharge: false,
            taxCategoryCode: 'Z',
            taxTypeCode: 'VAT',
            rateApplicablePercent: (float)0.0,
            reason: 'Discount',
        );

        $builder->addDocumentAllowanceCharge(
            actualAmount: (float)$order->getFieldData('OXVOUCHERDISCOUNT'),
            isCharge: false,
            taxCategoryCode: 'Z',
            taxTypeCode: 'VAT',
            rateApplicablePercent: (float)0.0,
            reason: 'Voucher',
        );

        return $builder;
    }
}
