<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\ElectronicInvoice\ZUGFeRD\BuilderConfigurator;

use FreshAdvance\Invoice\DataType\InvoiceDataInterface;
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

        foreach ($orderArticles as $key => $orderArticle) {
            $builder = $this->builderItemConfigurator->configureOneItem(
                builder: $builder,
                invoiceData: $invoiceData,
                position: $key + 1,
                orderArticle: $orderArticle,
            );
        }

        return $builder;
    }
}
