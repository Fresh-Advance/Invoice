<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\DataType;

use FreshAdvance\Invoice\DataType\InvoiceConfiguration;
use PHPUnit\Framework\Attributes\DataProvider;
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

    #[DataProvider('formattedDateDataProvider')]
    public function testGetFormattedDate(string $format, string $expectation): void
    {
        $sut = new InvoiceConfiguration(
            orderId: 'someOrderId',
            signer: 'someSigner',
            date: $format,
            number: 'for%1$smat'
        );

        $this->assertSame($expectation, $sut->getFormattedDate());
    }

    public static function formattedDateDataProvider(): \Generator
    {
        $y = date('Y');
        $m = date('m');
        $d = date('d');

        yield "formatted" => ['~Y~m~d~', "~{$y}~{$m}~{$d}~"];
        yield "regular format" => ['Y-m-d', "{$y}-{$m}-{$d}"];
        yield "empty case" => ['', ''];
        yield "numbers as format" => ['123-456', '123-456'];
    }
}
