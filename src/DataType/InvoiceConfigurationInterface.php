<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\DataType;

interface InvoiceConfigurationInterface
{
    public function getOrderId(): string;

    public function getSigner(): string;

    public function getDate(): string;

    public function getNumber(): string;
}
