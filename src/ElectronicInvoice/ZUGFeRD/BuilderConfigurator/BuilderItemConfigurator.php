<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\ElectronicInvoice\ZUGFeRD\BuilderConfigurator;

use FreshAdvance\Invoice\DataType\InvoiceDataInterface;
use FreshAdvance\Invoice\Pdf\Model\OrderArticleExtension;
use horstoeko\zugferd\ZugferdDocumentBuilder;

class BuilderItemConfigurator implements BuilderItemConfiguratorInterface
{
    public function configureOneItem(
        ZugferdDocumentBuilder $builder,
        InvoiceDataInterface $invoiceData,
        int $position,
        OrderArticleExtension $orderArticle,
    ): ZugferdDocumentBuilder {
        $builder->addNewPosition((string)$position);

        $builder->setDocumentPositionProductDetails(
            name: $orderArticle->faGetTranslatedTitle($invoiceData->getLanguageId()),
            sellerAssignedID: $orderArticle->getFieldData('OXARTNUM')
        );

        $builder->setDocumentPositionNetPrice((float)$orderArticle->getFieldData('OXNPRICE'));
        $builder->setDocumentPositionGrossPrice((float)$orderArticle->getFieldData('OXBPRICE'));
        $builder->setDocumentPositionQuantity($orderArticle->getFieldData('OXAMOUNT'), "H87");
        $builder->addDocumentPositionTax('S', 'VAT', $orderArticle->getFieldData('OXVAT'));
        $builder->setDocumentPositionLineSummation((float)$orderArticle->getFieldData('OXNETPRICE'));

        return $builder;
    }
}
