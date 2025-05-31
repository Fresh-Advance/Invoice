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

class BuilderItemConfiguratorIterator implements BuilderConfiguratorInterface
{
    public function __construct(
        private readonly BuilderItemConfiguratorInterface $builderItemConfigurator,
    ) {
    }

    public function configureBuilder(
        ZugferdDocumentBuilder $builder,
        InvoiceDataInterface $invoiceData
    ): ZugferdDocumentBuilder {
        $orderArticles = $invoiceData->getOrder()->getOrderArticles();

        $position = 0;
        /** @var OrderArticleExtension $orderArticle */
        foreach ($orderArticles as $orderArticle) {
            $builder = $this->builderItemConfigurator->configureOneItem(
                builder: $builder,
                invoiceData: $invoiceData,
                position: ++$position,
                orderArticle: $orderArticle,
            );
        }

        return $builder;
    }
}
