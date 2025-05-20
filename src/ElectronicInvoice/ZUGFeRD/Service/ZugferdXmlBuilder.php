<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\ElectronicInvoice\ZUGFeRD\Service;

use FreshAdvance\Invoice\DataType\InvoiceDataInterface;
use FreshAdvance\Invoice\ElectronicInvoice\ZUGFeRD\BuilderConfigurator\BuilderConfiguratorInterface;
use FreshAdvance\Invoice\ElectronicInvoice\ZUGFeRD\Factory\ZugferdXmlBuilderFactoryInterface;

class ZugferdXmlBuilder implements ZugferdXmlBuilderInterface
{
    public function __construct(
        private readonly ZugferdXmlBuilderFactoryInterface $builderFactory,
        private readonly BuilderConfiguratorInterface $builderConfigurator,
    ) {
    }

    public function getXml(InvoiceDataInterface $invoiceData): string
    {
        $builder = $this->builderFactory->createBuilder();
        $builder = $this->builderConfigurator->configureBuilder($builder, $invoiceData);

        return $builder->getContent();
    }
}
