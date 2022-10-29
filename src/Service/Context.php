<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Service;

use OxidEsales\EshopCommunity\Internal\Transition\Utility\BasicContextInterface as ShopContextInterface;
use Symfony\Component\Filesystem\Path;

class Context implements ContextInterface
{
    public const INVOICES_PARTIAL_PATH = 'invoices';

    public function __construct(
        protected ShopContextInterface $shopContext
    ) {
    }

    public function getInvoicesPath(): string
    {
        return Path::join(
            $this->shopContext->getShopRootPath(),
            self::INVOICES_PARTIAL_PATH
        );
    }
}
