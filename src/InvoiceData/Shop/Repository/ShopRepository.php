<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\InvoiceData\Shop\Repository;

use FreshAdvance\Invoice\InvoiceData\Shop\Exception\ShopNotFound;
use OxidEsales\Eshop\Application\Model\Shop as ShopModel;

class ShopRepository implements ShopRepositoryInterface
{
    /**
     * @throws ShopNotFound
     */
    public function getByShopId(int $shopId): ShopModel
    {
        $shop = oxNew(ShopModel::class);
        if (!$shop->load((string)$shopId)) {
            throw new ShopNotFound(sprintf('Order "%s" not found', $shopId));
        }

        return $shop;
    }
}
