<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\ElectronicInvoice\ZUGFeRD\BuilderConfigurator;

use FreshAdvance\Invoice\DataType\InvoiceDataInterface;
use FreshAdvance\Invoice\ElectronicInvoice\ZUGFeRD\BuilderConfigurator\BuilderItemConfigurator;
use FreshAdvance\Invoice\Pdf\Model\OrderArticleExtension;
use horstoeko\zugferd\ZugferdDocumentBuilder;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class BuilderItemConfiguratorTest extends TestCase
{
    #[Test]
    public function configuresBuilderWithItem(): void
    {
        $position = rand(1, 100);

        $invoiceDataStub = $this->createConfiguredStub(InvoiceDataInterface::class, [
            'getLanguageId' => $languageId = rand(0, 100),
        ]);

        $orderArticleMock = $this->createMock(OrderArticleExtension::class);
        $orderArticleMock->method('faGetTranslatedTitle')
            ->with($languageId)
            ->willReturn($title = uniqid());
        $orderArticleMock->method('getFieldData')
            ->willReturnMap([
                ['OXARTNUM', $artNum = uniqid()],
                ['OXNPRICE', (string)$oneNet = rand(10, 100)], // one net
                ['OXNETPRICE', (string)$totalNet = rand(100, 200)], // total net
                ['OXBPRICE', (string)$oneBrut = rand(10, 100)], // one brut
                ['OXAMOUNT', (string)$amount = rand(1, 10)],
                ['OXVAT', (string)$vat = rand(10, 100)], // VAT percentage
            ]);

        $builderSpy = $this->createMock(ZugferdDocumentBuilder::class);

        $builderSpy->expects($this->once())
            ->method('addNewPosition')
            ->with($position);

        $builderSpy->expects($this->once())
            ->method('setDocumentPositionProductDetails')
            ->with($title, null, $artNum);

        $builderSpy->expects($this->once())
            ->method('setDocumentPositionNetPrice')
            ->with($oneNet);

        $builderSpy->expects($this->once())
            ->method('setDocumentPositionQuantity')
            ->with($amount, 'H87');

        $builderSpy->expects($this->once())
            ->method('addDocumentPositionTax')
            ->with('S', 'VAT', $vat);

        $builderSpy->expects($this->once())
            ->method('setDocumentPositionLineSummation')
            ->with($totalNet);

        $sut = new BuilderItemConfigurator();

        $result = $sut->configureOneItem($builderSpy, $invoiceDataStub, $position, $orderArticleMock);

        $this->assertSame($builderSpy, $result);
    }
}
