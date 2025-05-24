<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Geo\Factory;

use OxidEsales\Eshop\Application\Model\Country;

class CountryModelFactory implements CountryModelFactoryInterface
{
    public function createModelObject()
    {
        return oxNew(Country::class);
    }
}
