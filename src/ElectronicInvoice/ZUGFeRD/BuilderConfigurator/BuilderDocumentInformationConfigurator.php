<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\ElectronicInvoice\ZUGFeRD\BuilderConfigurator;

use DateTime;
use FreshAdvance\Invoice\DataType\InvoiceDataInterface;
use horstoeko\zugferd\codelists\ZugferdInvoiceType;
use horstoeko\zugferd\ZugferdDocumentBuilder;

class BuilderDocumentInformationConfigurator implements BuilderConfiguratorInterface
{
    public function configureBuilder(
        ZugferdDocumentBuilder $builder,
        InvoiceDataInterface $invoiceData
    ): ZugferdDocumentBuilder {
        $order = $invoiceData->getOrder();
        $configuration = $invoiceData->getInvoiceConfiguration();

        $builder->setDocumentInformation(
            $configuration->getFormattedNumber($order->getFieldData('oxbillnr')),
            ZugferdInvoiceType::INVOICE,
            new DateTime($configuration->getFormattedDate()),
            $order->getOrderCurrency()->name,
        );

        return $builder;
    }
}
