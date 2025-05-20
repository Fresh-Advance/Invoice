<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\ElectronicInvoice\ZUGFeRD\BuilderConfigurator;

use FreshAdvance\Invoice\DataType\InvoiceDataInterface;
use FreshAdvance\Invoice\ElectronicInvoice\ZUGFeRD\BuilderConfigurator\BuilderConfiguratorComposite;
use FreshAdvance\Invoice\ElectronicInvoice\ZUGFeRD\BuilderConfigurator\BuilderConfiguratorInterface;
use horstoeko\zugferd\ZugferdDocumentBuilder;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class BuilderConfiguratorCompositeTest extends TestCase
{
    #[Test]
    public function compositeTriggersAllInnerConfigurators(): void
    {
        $invoiceData = $this->createStub(InvoiceDataInterface::class);
        $notConfiguredBuilder = $this->createStub(ZugferdDocumentBuilder::class);

        $configuredBuilder1 = $this->createStub(ZugferdDocumentBuilder::class);
        $configuredBuilder2 = $this->createStub(ZugferdDocumentBuilder::class);

        $configurator1 = $this->createMock(BuilderConfiguratorInterface::class);
        $configurator1->method('configureBuilder')
            ->with($notConfiguredBuilder, $invoiceData)
            ->willReturn($configuredBuilder1);

        $configurator2 = $this->createMock(BuilderConfiguratorInterface::class);
        $configurator2->method('configureBuilder')
            ->with($configuredBuilder1, $invoiceData)
            ->willReturn($configuredBuilder2);

        $sut = new BuilderConfiguratorComposite(
            $configurator1,
            $configurator2
        );

        $result = $sut->configureBuilder($notConfiguredBuilder, $invoiceData);
        $this->assertSame($configuredBuilder2, $result);
    }
}
