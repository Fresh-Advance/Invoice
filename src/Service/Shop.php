<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Service;

use FreshAdvance\Invoice\Exception\ShopNotFound;
use OxidEsales\Eshop\Application\Model\Shop as ShopModel;
use OxidEsales\EshopCommunity\Internal\Transition\Utility\ContextInterface;

class Shop
{
    public function __construct(
        protected ContextInterface $context,
    ) {
    }

    public function getActiveShop(): ShopModel
    {
        $shopId = $this->context->getCurrentShopId();
        $shop = oxNew(ShopModel::class);
        if (!$shop->load((string)$shopId)) {
            throw new ShopNotFound();
        }

        return $shop;
    }
}
