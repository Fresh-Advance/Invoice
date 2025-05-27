<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\ElectronicInvoice\ZUGFeRD\BuilderConfigurator;

use FreshAdvance\Invoice\DataType\InvoiceDataInterface;
use FreshAdvance\Invoice\ElectronicInvoice\ZUGFeRD\BuilderConfigurator\BuilderBuyerConfigurator;
use FreshAdvance\Invoice\ElectronicInvoice\ZUGFeRD\BuilderConfigurator\BuilderConfiguratorInterface;
use FreshAdvance\Invoice\Geo\Service\GeoServiceInterface;
use horstoeko\zugferd\codelists\ZugferdElectronicAddressScheme;
use horstoeko\zugferd\ZugferdDocumentBuilder;
use OxidEsales\Eshop\Application\Model\Order;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class BuilderBuyerConfiguratorTest extends TestCase
{
    public static function sellerCalculationDataProvider(): \Generator
    {
        yield 'seller as company' => [
            'orderDataMap' => [
                ['OXBILLCOMPANY', $companyName = uniqid()],
                ['OXBILLFNAME', uniqid()],
                ['OXBILLLNAME', uniqid()],
                ['OXBILLCOUNTRYID', uniqid()],
            ],
            'expectedSeller' => $companyName,
        ];

        yield 'seller as name with surname' => [
            'orderDataMap' => [
                ['OXBILLCOMPANY', ''],
                ['OXBILLFNAME', $firstName = uniqid()],
                ['OXBILLLNAME', $lastName = uniqid()],
                ['OXBILLCOUNTRYID', uniqid()],
            ],
            'expectedSeller' => $firstName . ' ' . $lastName,
        ];
    }

    #[Test]
    #[DataProvider('sellerCalculationDataProvider')]
    public function builderConfiguredWithCorrectSellerName(array $orderDataMap, string $expectedSeller): void
    {
        $orderStub = $this->createMock(Order::class);
        $orderStub->method('getFieldData')
            ->willReturnMap($orderDataMap);

        $invoiceDataStub = $this->createConfiguredStub(InvoiceDataInterface::class, [
            'getOrder' => $orderStub,
        ]);

        $builderSpy = $this->createMock(ZugferdDocumentBuilder::class);

        $builderSpy->expects($this->once())
            ->method('setDocumentBuyer')
            ->with($expectedSeller);

        $geoServiceMock = $this->createMock(GeoServiceInterface::class);
        $geoServiceMock->method('getCountryCodeById')
            ->willReturn(uniqid());

        $sut = $this->getSut(
            geoServiceMock: $geoServiceMock,
        );

        $result = $sut->configureBuilder($builderSpy, $invoiceDataStub);
        $this->assertSame($builderSpy, $result);
    }

    #[Test]
    public function builderConfiguredWithBuyerInformationAndReturned(): void
    {
        $orderStub = $this->createMock(Order::class);
        $orderStub->method('getFieldData')
            ->willReturnMap([
                ['OXBILLCOUNTRYID', $countryId = uniqid()],
                ['OXBILLCITY', $city = uniqid()],
                ['OXBILLSTREET', $street = uniqid()],
                ['OXBILLSTREETNR', $streetNr = uniqid()],
                ['OXBILLZIP', $zip = uniqid()],
                ['OXBILLEMAIL', $email = uniqid()],
                ['OXBILLFNAME', $firstName = uniqid()],
                ['OXBILLLNAME', $lastName = uniqid()],
                ['OXBILLFON', $phone = uniqid()],
                ['OXBILLFAX', $fax = uniqid()],
            ]);

        $invoiceDataStub = $this->createConfiguredStub(InvoiceDataInterface::class, [
            'getOrder' => $orderStub,
        ]);

        $geoServiceMock = $this->createMock(GeoServiceInterface::class);
        $geoServiceMock->method('getCountryCodeById')
            ->with($countryId)
            ->willReturn($countryCode = uniqid());

        $builderSpy = $this->createMock(ZugferdDocumentBuilder::class);

        $builderSpy->expects($this->once())
            ->method('setDocumentBuyerAddress')
            ->with(
                $street . ' ' . $streetNr,
                null,
                null,
                $zip,
                $city,
                $countryCode,
            );

        $builderSpy->expects($this->once())
            ->method('setDocumentBuyerContact')
            ->with(
                $firstName . ' ' . $lastName,
                null,
                $phone,
                $fax,
                $email,
            );

        $builderSpy->expects($this->once())
            ->method('setDocumentBuyerCommunication')
            ->with(
                ZugferdElectronicAddressScheme::UNECE3155_EM,
                $email,
            );

        $sut = $this->getSut(
            geoServiceMock: $geoServiceMock
        );

        $result = $sut->configureBuilder($builderSpy, $invoiceDataStub);
        $this->assertSame($builderSpy, $result);
    }

    public function getSut(
        ?GeoServiceInterface $geoServiceMock = null,
    ): BuilderConfiguratorInterface {
        return new BuilderBuyerConfigurator(
            geoService: $geoServiceMock ?? $this->createStub(GeoServiceInterface::class),
        );
    }
}
