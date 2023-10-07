<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\DataType;

use OxidEsales\Eshop\Application\Model\Order;
use OxidEsales\Eshop\Application\Model\Shop;

interface InvoiceDataInterface
{
    public function getOrder(): Order;

    public function getShop(): Shop;

    public function getLanguageId(): int;

    /**
     * Path to generated invoice file in the filesystem
     */
    public function getInvoicePath(): string;

    public function getInvoiceConfiguration(): InvoiceConfigurationInterface;
}
