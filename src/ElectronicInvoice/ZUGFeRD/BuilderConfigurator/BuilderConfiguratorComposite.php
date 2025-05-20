<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\ElectronicInvoice\ZUGFeRD\BuilderConfigurator;

use FreshAdvance\Invoice\DataType\InvoiceDataInterface;
use horstoeko\zugferd\ZugferdDocumentBuilder;

class BuilderConfiguratorComposite implements BuilderConfiguratorInterface
{
    /** @var BuilderConfiguratorInterface[] */
    private array $builderConfigurators = [];

    public function __construct(BuilderConfiguratorInterface ...$builderConfigurators)
    {
        $this->builderConfigurators = $builderConfigurators;
    }

    public function configureBuilder(
        ZugferdDocumentBuilder $builder,
        InvoiceDataInterface $invoiceData
    ): ZugferdDocumentBuilder {
        foreach ($this->builderConfigurators as $builderConfigurator) {
            $builder = $builderConfigurator->configureBuilder($builder, $invoiceData);
        }

        return $builder;
    }
}
