<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\ElectronicInvoice\ZUGFeRD\BuilderConfigurator;

use FreshAdvance\Invoice\DataType\InvoiceDataInterface;
use FreshAdvance\Invoice\ElectronicInvoice\ZUGFeRD\BuilderConfigurator\BuilderSellerConfigurator;
use horstoeko\zugferd\codelists\ZugferdElectronicAddressScheme;
use horstoeko\zugferd\ZugferdDocumentBuilder;
use OxidEsales\Eshop\Application\Model\Shop;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class BuilderSellerConfiguratorTest extends TestCase
{
    #[Test]
    public function builderConfiguredWithSellerInformationAndReturned(): void
    {
        $shopStub = $this->createMock(Shop::class);
        $shopStub->method('getFieldData')
            ->willReturnMap([
                ['OXCOMPANY', $companyName = uniqid()],
                ['OXCOUNTRY', $companyCountry = uniqid()],
                ['OXCITY', $companyCity = uniqid()],
                ['OXSTREET', $companyStreet = uniqid()],
                ['OXZIP', $companyZip = uniqid()],
                ['OXVATNUMBER', $companyVatID = uniqid()],
                ['OXTAXNUMBER', $companyTaxID = uniqid()],
                ['OXINFOEMAIL', $companyEmail = uniqid()],
                ['OXFNAME', $contactFirstName = uniqid()],
                ['OXLNAME', $contactLastName = uniqid()],
                ['OXTELEFON', $contactPhone = uniqid()],
                ['OXTELEFAX', $contactFax = uniqid()],
            ]);

        $invoiceDataStub = $this->createConfiguredStub(InvoiceDataInterface::class, [
            'getShop' => $shopStub,
        ]);

        $builderSpy = $this->createMock(ZugferdDocumentBuilder::class);

        $builderSpy->expects($this->once())
            ->method('setDocumentSeller')
            ->with($companyName);

        $builderSpy->expects($this->once())
            ->method('addDocumentSellerTaxNumber')
            ->with(taxNo: $companyTaxID);

        $builderSpy->expects($this->once())
            ->method('addDocumentSellerVATRegistrationNumber')
            ->with(vatRegNo: $companyVatID);

        $builderSpy->expects($this->once())
            ->method('setDocumentSellerAddress')
            ->with(
                lineOne: $companyStreet,
                lineTwo: null,
                lineThree: null,
                postCode: $companyZip,
                city: $companyCity,
                country: $companyCountry
            );

        $builderSpy->expects($this->once())
            ->method('setDocumentSellerContact')
            ->with(
                contactPersonName: sprintf('%s %s', $contactFirstName, $contactLastName),
                contactDepartmentName: null,
                contactPhoneNo: $contactPhone,
                contactFaxNo: $contactFax,
                contactEmailAddress: $companyEmail,
            );

        $builderSpy->expects($this->once())
            ->method('setDocumentSellerCommunication')
            ->with(
                ZugferdElectronicAddressScheme::UNECE3155_EM,
                $companyEmail,
            );


        $sut = new BuilderSellerConfigurator();

        $result = $sut->configureBuilder($builderSpy, $invoiceDataStub);
        $this->assertSame($builderSpy, $result);
    }
}
