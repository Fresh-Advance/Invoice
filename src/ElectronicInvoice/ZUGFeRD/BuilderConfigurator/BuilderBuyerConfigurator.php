<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\ElectronicInvoice\ZUGFeRD\BuilderConfigurator;

use FreshAdvance\Invoice\DataType\InvoiceDataInterface;
use horstoeko\zugferd\ZugferdDocumentBuilder;

class BuilderBuyerConfigurator implements BuilderConfiguratorInterface
{
    public function configureBuilder(
        ZugferdDocumentBuilder $builder,
        InvoiceDataInterface $invoiceData
    ): ZugferdDocumentBuilder {
        $order = $invoiceData->getOrder();

        $sellerName = $order->getFieldData('OXBILLCOMPANY')
            ?: trim($order->getFieldData('OXBILLFNAME') . ' ' . $order->getFieldData('OXBILLLNAME'));
        $builder->setDocumentBuyer($sellerName);

//        $builder->setDocumentBuyer('Kunden AG Mitte', 'GE2020211');
//        $builder->setDocumentBuyerAddress('Kundenstraße 15', '', '', '69876', 'Frankfurt', ZugferdCountryCodes::GERMANY);
//        $builder->setDocumentBuyerContact('H. Meier', 'Einkauf', '+49-333-4444444', '+49-333-5555555', 'hm@kunde.de');
//        $builder->setDocumentBuyerCommunication(ZugferdElectronicAddressScheme::UNECE3155_EM, 'purchase@kunde.de');


        return $builder;
    }
}
