<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\ElectronicInvoice\ZUGFeRD\BuilderConfigurator;

use FreshAdvance\Invoice\DataType\InvoiceDataInterface;
use horstoeko\zugferd\codelists\ZugferdElectronicAddressScheme;
use horstoeko\zugferd\ZugferdDocumentBuilder;

class BuilderSellerConfigurator implements BuilderConfiguratorInterface
{
    public function configureBuilder(
        ZugferdDocumentBuilder $builder,
        InvoiceDataInterface $invoiceData
    ): ZugferdDocumentBuilder {
        $shop = $invoiceData->getShop();

        $builder->setDocumentSeller(
            name: $shop->getFieldData('OXCOMPANY'),
        );

        $builder->addDocumentSellerTaxNumber(
            taxNo: $shop->getFieldData('OXTAXNUMBER'),
        );

        $builder->addDocumentSellerVATRegistrationNumber(
            vatRegNo: $shop->getFieldData('OXVATNUMBER'),
        );

        $builder->setDocumentSellerAddress(
            lineOne: $shop->getFieldData('OXSTREET'),
            postCode: $shop->getFieldData('OXZIP'),
            city: $shop->getFieldData('OXCITY'),
            country: $shop->getFieldData('OXCOUNTRY'),
        );

        $builder->setDocumentSellerContact(
            contactPersonName: sprintf(
                '%s %s',
                $shop->getFieldData('OXFNAME'),
                $shop->getFieldData('OXLNAME'),
            ),
            contactDepartmentName: null,
            contactPhoneNo: $shop->getFieldData('OXTELEFON'),
            contactFaxNo: $shop->getFieldData('OXTELEFAX'),
            contactEmailAddress: $shop->getFieldData('OXINFOEMAIL'),
        );

        $builder->setDocumentSellerCommunication(
            uriScheme: ZugferdElectronicAddressScheme::UNECE3155_EM,
            uri: $shop->getFieldData('OXINFOEMAIL'),
        );

        return $builder;
    }
}
