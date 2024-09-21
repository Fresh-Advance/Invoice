<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\DataType;

use FreshAdvance\Invoice\DataType\InvoiceConfiguration;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FreshAdvance\Invoice\DataType\InvoiceConfiguration
 */
class InvoiceConfigurationTest extends TestCase
{
    public function testGetters(): void
    {
        $sut = new InvoiceConfiguration(
            orderId: 'someOrderId',
            signer: 'someSigner',
            date: 'someDate',
            number: 'someNumber'
        );

        $this->assertSame('someOrderId', $sut->getOrderId());
        $this->assertSame('someDate', $sut->getDate());
        $this->assertSame('someSigner', $sut->getSigner());
        $this->assertSame('someNumber', $sut->getNumber());
    }

    public function testGetFormattedNumber(): void
    {
        $sut = new InvoiceConfiguration(
            orderId: 'someOrderId',
            signer: 'someSigner',
            date: 'someDate',
            number: 'for%1$smat'
        );

        $invoiceNumber = uniqid();
        $this->assertSame('for' . $invoiceNumber . 'mat', $sut->getFormattedNumber($invoiceNumber));
    }
}
