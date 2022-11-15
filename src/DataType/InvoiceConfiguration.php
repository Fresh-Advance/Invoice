<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\DataType;

class InvoiceConfiguration implements InvoiceConfigurationInterface
{
    public function __construct(
        protected string $orderId,
        protected string $signer = '',
        protected string $date = '',
        protected string $number = ''
    ) {
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }

    public function getSigner(): string
    {
        return $this->signer;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getNumber(): string
    {
        return $this->number;
    }
}
