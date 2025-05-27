<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\ElectronicInvoice\ZUGFeRD\BuilderConfigurator;

use FreshAdvance\Invoice\DataType\InvoiceDataInterface;
use FreshAdvance\Invoice\Geo\Service\GeoServiceInterface;
use horstoeko\zugferd\codelists\ZugferdElectronicAddressScheme;
use horstoeko\zugferd\ZugferdDocumentBuilder;

class BuilderBuyerConfigurator implements BuilderConfiguratorInterface
{
    public function __construct(
        private readonly GeoServiceInterface $geoService,
    ) {
    }

    public function configureBuilder(
        ZugferdDocumentBuilder $builder,
        InvoiceDataInterface $invoiceData
    ): ZugferdDocumentBuilder {
        $order = $invoiceData->getOrder();

        $sellerName = $order->getFieldData('OXBILLCOMPANY')
            ?: trim($order->getFieldData('OXBILLFNAME') . ' ' . $order->getFieldData('OXBILLLNAME'));
        $builder->setDocumentBuyer((string)$sellerName);

        $builder->setDocumentBuyerAddress(
            lineOne: trim($order->getFieldData('OXBILLSTREET') . ' ' . $order->getFieldData('OXBILLSTREETNR')),
            postCode: (string)$order->getFieldData('OXBILLZIP'),
            city: (string)$order->getFieldData('OXBILLCITY'),
            country: $this->geoService->getCountryCodeById((string)$order->getFieldData('OXBILLCOUNTRYID'))
        );

        $builder->setDocumentBuyerContact(
            contactPersonName: trim($order->getFieldData('OXBILLFNAME') . ' ' . $order->getFieldData('OXBILLLNAME')),
            contactDepartmentName: null,
            contactPhoneNo: (string)$order->getFieldData('OXBILLFON'),
            contactFaxNo: (string)$order->getFieldData('OXBILLFAX'),
            contactEmailAddress: (string)$order->getFieldData('OXBILLEMAIL'),
        );

        $builder->setDocumentBuyerCommunication(
            ZugferdElectronicAddressScheme::UNECE3155_EM,
            (string)$order->getFieldData('OXBILLEMAIL'),
        );

        return $builder;
    }
}
