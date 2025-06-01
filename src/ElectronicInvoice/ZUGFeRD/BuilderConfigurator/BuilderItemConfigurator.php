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
            sellerAssignedID: (string)$orderArticle->getFieldData('OXARTNUM')
        );

        $builder->setDocumentPositionNetPrice((float)$orderArticle->getFieldData('OXNPRICE'));
        $builder->setDocumentPositionGrossPrice((float)$orderArticle->getFieldData('OXBPRICE'));
        $builder->setDocumentPositionQuantity((float)$orderArticle->getFieldData('OXAMOUNT'), "H87");

        // todo: category code Standard doesnt fit if there are no applied, we should check at least this case.
        $builder->addDocumentPositionTax('S', 'VAT', (float)$orderArticle->getFieldData('OXVAT'));

        $builder->setDocumentPositionLineSummation((float)$orderArticle->getFieldData('OXNETPRICE'));

        return $builder;
    }
}
