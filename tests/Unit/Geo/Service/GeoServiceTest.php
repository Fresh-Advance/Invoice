<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\Geo\Service;

use FreshAdvance\Invoice\Geo\Factory\CountryModelFactoryInterface;
use FreshAdvance\Invoice\Geo\Service\GeoService;
use OxidEsales\Eshop\Application\Model\Country;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class GeoServiceTest extends TestCase
{
    #[Test]
    public function getCountryCodeByIdReturnsCountryIso2Code(): void
    {
        $id = uniqid();

        $countryModelSpy = $this->createMock(Country::class);
        $countryModelSpy->expects($this->once())
            ->method('load')
            ->with($id);
        $countryModelSpy->method('getFieldData')->willReturnMap([
            ['OXISOALPHA2', $countryCode = uniqid()],
        ]);

        $countryModelFactoryStub = $this->createConfiguredStub(CountryModelFactoryInterface::class, [
            'createModelObject' => $countryModelSpy,
        ]);

        $sut = new GeoService(
            countryModelFactory: $countryModelFactoryStub,
        );

        $result = $sut->getCountryCodeById($id);

        $this->assertSame($countryCode, $result);
    }

    #[Test]
    public function getCountryCodeByIdReturnsNullForNotFoundCountry(): void
    {
        $id = uniqid();

        $countryModelSpy = $this->createMock(Country::class);
        $countryModelSpy->expects($this->once())
            ->method('load')
            ->with($id)
            ->willThrowException(new \Exception());

        $countryModelFactoryStub = $this->createConfiguredStub(CountryModelFactoryInterface::class, [
            'createModelObject' => $countryModelSpy,
        ]);

        $sut = new GeoService(
            countryModelFactory: $countryModelFactoryStub,
        );

        $result = $sut->getCountryCodeById($id);

        $this->assertNull($result);
    }
}
