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

class BuilderVatConfigurator implements BuilderConfiguratorInterface
{
    public function configureBuilder(
        ZugferdDocumentBuilder $builder,
        InvoiceDataInterface $invoiceData
    ): ZugferdDocumentBuilder {
        $rates = $this->getOrderVatRates($invoiceData->getOrder());

        foreach ($rates as $vatRate => $values) {
            $builder->addDocumentTax(
                "S",
                "VAT",
                (float)$values['net'],
                (float)$values['vat'],
                (float)$vatRate
            );
        }

        return $builder;
    }

    /**
     * @return array<float, array{net: float, vat: float}>
     */
    private function getOrderVatRates(Order $order): array
    {
        $rates = [];

        $items = $order->getOrderArticles();
        foreach ($items as $oneItem) {
            if (!isset($rates[$oneItem->getFieldData('OXVAT')])) {
                $rates[$oneItem->getFieldData('OXVAT')] = [
                    'net' => 0.0,
                    'vat' => 0.0,
                ];
            }

            $rates[$oneItem->getFieldData('OXVAT')]['net'] += $oneItem->getFieldData('OXNETPRICE');
            $rates[$oneItem->getFieldData('OXVAT')]['vat'] += $oneItem->getFieldData('OXVATPRICE');
        }
        return $rates;
    }
}
