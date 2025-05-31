<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\ElectronicInvoice\ZUGFeRD\BuilderConfigurator;

use FreshAdvance\Invoice\DataType\InvoiceDataInterface;
use FreshAdvance\Invoice\ElectronicInvoice\ZUGFeRD\BuilderConfigurator\BuilderItemConfiguratorInterface;
use FreshAdvance\Invoice\ElectronicInvoice\ZUGFeRD\BuilderConfigurator\BuilderItemConfiguratorIterator;
use FreshAdvance\Invoice\Pdf\Model\OrderArticleExtension;
use horstoeko\zugferd\ZugferdDocumentBuilder;
use OxidEsales\Eshop\Application\Model\Order;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class BuilderItemConfiguratorIteratorTest extends TestCase
{
    #[Test]
    public function iteratesThroughItemsAndTriggersBuilderConfigurationForEach(): void
    {
        $invoiceDataStub = $this->createConfiguredStub(InvoiceDataInterface::class, [
            'getOrder' => $this->createConfiguredStub(Order::class, [
                'getOrderArticles' => [
                    "10" => $item1 = $this->createStub(OrderArticleExtension::class),
                    "20" => $item2 = $this->createStub(OrderArticleExtension::class),
                ],
            ]),
        ]);

        $builderStub = $this->createMock(ZugferdDocumentBuilder::class);

        $builderItemConfiguratorSpy = $this->createMock(BuilderItemConfiguratorInterface::class);
        $builderItemConfiguratorSpy->expects($counter = $this->exactly(2))
            ->method('configureOneItem')
            ->willReturnCallback(function (
                ZugferdDocumentBuilder $builder,
                InvoiceDataInterface $invoiceData,
                int $position,
                OrderArticleExtension $orderArticle
            ) use (
                $counter,
                $builderStub,
                $invoiceDataStub,
                $item1,
                $item2
            ): ZugferdDocumentBuilder {
                $this->assertSame($invoiceDataStub, $invoiceData);

                switch ($counter->numberOfInvocations()) {
                    case 1:
                        $this->assertSame(1, $position);
                        $this->assertSame($item1, $orderArticle);
                        break;
                    case 2:
                        $this->assertSame(2, $position);
                        $this->assertSame($item2, $orderArticle);
                        break;
                }

                return $builder;
            });

        $sut = new BuilderItemConfiguratorIterator(
            builderItemConfigurator: $builderItemConfiguratorSpy,
        );

        $result = $sut->configureBuilder($builderStub, $invoiceDataStub);
        $this->assertSame($builderStub, $result);
    }
}
