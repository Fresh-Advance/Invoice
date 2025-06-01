<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\ElectronicInvoice\ZUGFeRD\BuilderConfigurator;

use FreshAdvance\Invoice\DataType\InvoiceConfigurationInterface;
use FreshAdvance\Invoice\DataType\InvoiceDataInterface;
use FreshAdvance\Invoice\ElectronicInvoice\ZUGFeRD\BuilderConfigurator\BuilderDocumentInformationConfigurator;
use horstoeko\zugferd\codelists\ZugferdInvoiceType;
use horstoeko\zugferd\ZugferdDocumentBuilder;
use OxidEsales\Eshop\Application\Model\Order;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use stdClass;

class BuilderDocumentInformationConfiguratorTest extends TestCase
{
    #[Test]
    public function builderConfiguredWithDocumentInformationAndReturned(): void
    {
        $currencyStub = new stdClass();
        $currencyStub->name = $currencyName = uniqid();

        $orderStub = $this->createMock(Order::class);
        $orderStub->method('getFieldData')->willReturnMap([
            ['oxbillnr', $billNr = rand(1, 100)],
        ]);
        $orderStub->method('getOrderCurrency')
            ->willReturn($currencyStub);

        $invoiceConfigurationMock = $this->createMock(InvoiceConfigurationInterface::class);
        $invoiceConfigurationMock->method('getFormattedDate')
            ->willReturn($date = date('Y-m-d'));
        $invoiceConfigurationMock->method('getFormattedNumber')
            ->with($billNr)
            ->willReturn($formattedNumber = uniqid());

        $invoiceDataStub = $this->createConfiguredStub(InvoiceDataInterface::class, [
            'getOrder' => $orderStub,
            'getInvoiceConfiguration' => $invoiceConfigurationMock
        ]);

        $builderSpy = $this->createMock(ZugferdDocumentBuilder::class);
        $builderSpy->expects($this->once())
            ->method('setDocumentInformation')
            ->with(
                $formattedNumber,
                ZugferdInvoiceType::INVOICE,
                new \DateTime($date),
                $currencyName
            )
            ->willReturn($builderSpy);

        $builderSpy->expects($this->once())
            ->method('setDocumentBusinessProcess')
            ->with(BuilderDocumentInformationConfigurator::PROCESS_ID)
            ->willReturn($builderSpy);

        $sut = new BuilderDocumentInformationConfigurator();

        $result = $sut->configureBuilder($builderSpy, $invoiceDataStub);
        $this->assertSame($builderSpy, $result);
    }
}
