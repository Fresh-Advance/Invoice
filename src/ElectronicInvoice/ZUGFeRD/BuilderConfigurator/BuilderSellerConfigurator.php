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
            name: (string)$shop->getFieldData('OXCOMPANY'),
        );

        $builder->addDocumentSellerTaxNumber(
            taxNo: (string)$shop->getFieldData('OXTAXNUMBER'),
        );

        $builder->addDocumentSellerVATRegistrationNumber(
            vatRegNo: (string)$shop->getFieldData('OXVATNUMBER'),
        );

        //@todo country should be iso2, not the name

        $builder->setDocumentSellerAddress(
            lineOne: (string)$shop->getFieldData('OXSTREET'),
            postCode: (string)$shop->getFieldData('OXZIP'),
            city: (string)$shop->getFieldData('OXCITY'),
            country: (string)$shop->getFieldData('OXCOUNTRY'),
        );

        $builder->setDocumentSellerContact(
            contactPersonName: sprintf(
                '%s %s',
                $shop->getFieldData('OXFNAME'),
                $shop->getFieldData('OXLNAME'),
            ),
            contactDepartmentName: null,
            contactPhoneNo: (string)$shop->getFieldData('OXTELEFON'),
            contactFaxNo: (string)$shop->getFieldData('OXTELEFAX'),
            contactEmailAddress: (string)$shop->getFieldData('OXINFOEMAIL'),
        );

        $builder->setDocumentSellerCommunication(
            uriScheme: ZugferdElectronicAddressScheme::UNECE3155_EM,
            uri: (string)$shop->getFieldData('OXINFOEMAIL'),
        );

        //@todo: $documentBuilder->setDocumentSellerOrderReferencedDocument('SO-2024-000993337');

        return $builder;
    }
}
