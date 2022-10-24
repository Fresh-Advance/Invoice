<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Service;

use FreshAdvance\Invoice\Exception\ShopNotFound;
use OxidEsales\Eshop\Application\Model\Shop as ShopModel;

class Shop
{
    /**
     * @throws ShopNotFound
     */
    public function getShop(string $shopId): ShopModel
    {
        $shop = oxNew(ShopModel::class);
        if (!$shop->load($shopId)) {
            throw new ShopNotFound(sprintf('Order "%s" not found', $shopId));
        }

        return $shop;
    }
}
