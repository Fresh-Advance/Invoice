<?php

namespace FreshAdvance\Invoice\DataType;

interface InvoiceConfigurationInterface
{
    public function getOrderId(): string;

    public function getSigner(): string;

    public function getDate(): string;

    public function getNumber(): string;
}