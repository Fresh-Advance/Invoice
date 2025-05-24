<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Geo\Factory;

use FreshAdvance\Invoice\Geo\Factory\CountryModelFactory;
use OxidEsales\Eshop\Application\Model\Country;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class CountryModelFactoryTest extends TestCase
{
    #[Test]
    public function createModelObjectCreatesNewObjectEveryTime(): void
    {
        $sut = new CountryModelFactory();

        $item1 = $sut->createModelObject();
        $item2 = $sut->createModelObject();

        $this->assertNotSame($item1, $item2);
    }

    #[Test]
    public function createModelObjectCreatesModelObject(): void
    {
        $sut = new CountryModelFactory();
        $item = $sut->createModelObject();

        $this->assertInstanceOf(Country::class, $item);
    }
}
