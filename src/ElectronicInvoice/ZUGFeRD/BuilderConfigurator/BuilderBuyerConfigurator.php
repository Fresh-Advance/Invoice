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
        $builder->setDocumentBuyer($sellerName);

        $builder->setDocumentBuyerAddress(
            lineOne: trim($order->getFieldData('OXBILLSTREET') . ' ' . $order->getFieldData('OXBILLSTREETNR')),
            postCode: $order->getFieldData('OXBILLZIP'),
            city: $order->getFieldData('OXBILLCITY'),
            country: $this->geoService->getCountryCodeById($order->getFieldData('OXBILLCOUNTRYID'))
        );

        $builder->setDocumentBuyerContact(
            contactPersonName: trim($order->getFieldData('OXBILLFNAME') . ' ' . $order->getFieldData('OXBILLLNAME')),
            contactDepartmentName: null,
            contactPhoneNo: $order->getFieldData('OXBILLFON'),
            contactFaxNo: $order->getFieldData('OXBILLFAX'),
            contactEmailAddress: $order->getFieldData('OXBILLEMAIL'),
        );

        $builder->setDocumentBuyerCommunication(
            ZugferdElectronicAddressScheme::UNECE3155_EM,
            $order->getFieldData('OXBILLEMAIL'),
        );

        return $builder;
    }
}
