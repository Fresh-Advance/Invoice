<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Repository;

use FreshAdvance\Invoice\Exception\ShopNotFound;
use OxidEsales\Eshop\Application\Model\Shop as ShopModel;

interface ShopRepositoryInterface
{
    /**
     * @throws ShopNotFound
     */
    public function getByShopId(int $shopId): ShopModel;
}
