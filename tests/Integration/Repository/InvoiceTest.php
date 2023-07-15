<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Integration\Repository;

use FreshAdvance\Invoice\DataType\InvoiceConfiguration;
use FreshAdvance\Invoice\DataType\InvoiceConfigurationInterface;
use FreshAdvance\Invoice\Repository\InvoiceConfigurationRepository;
use OxidEsales\EshopCommunity\Tests\Integration\IntegrationTestCase;

/**
 * @covers \FreshAdvance\Invoice\Repository\InvoiceConfigurationRepository
 */
class InvoiceTest extends IntegrationTestCase
{
    protected const TEST_ORDER_ID = 'someTestOrderId';
    protected const TEST_NOT_EXISTING_ORDER_ID = 'someNotExistingOrderId';

    public function setUp(): void
    {
        parent::setUp();

        $sut = $this->getSut();
        $sut->saveInvoiceConfiguration(
            new InvoiceConfiguration(
                orderId: self::TEST_ORDER_ID,
                signer: 'someSigner',
                date: 'someDate',
                number: 'someNumber'
            )
        );
    }

    public function testGetNotExistingOrderInvoice(): void
    {
        $sut = $this->getSut();
        $this->assertNull($sut->getOrderInvoice(self::TEST_NOT_EXISTING_ORDER_ID));
    }

    public function testExistingOrderInvoice(): void
    {
        $sut = $this->getSut();

        $data = $sut->getOrderInvoice(self::TEST_ORDER_ID);
        $this->assertInstanceOf(InvoiceConfigurationInterface::class, $data);

        $this->assertSame(self::TEST_ORDER_ID, $data->getOrderId());
        $this->assertSame('someSigner', $data->getSigner());
        $this->assertSame('someDate', $data->getDate());
        $this->assertSame('someNumber', $data->getNumber());
    }

    /**
     * @dataProvider saveInvoiceConfigurationDataProvider
     */
    public function testSaveInvoiceConfiguration(string $orderId): void
    {
        $sut = $this->getSut();
        $sut->saveInvoiceConfiguration(
            new InvoiceConfiguration(
                orderId: $orderId,
                signer: 'someOtherSigner',
                date: 'someOtherDate',
                number: 'someOtherNumber'
            )
        );

        $data = $sut->getOrderInvoice($orderId);
        $this->assertInstanceOf(InvoiceConfigurationInterface::class, $data);

        $this->assertSame($orderId, $data->getOrderId());
        $this->assertSame('someOtherSigner', $data->getSigner());
        $this->assertSame('someOtherDate', $data->getDate());
        $this->assertSame('someOtherNumber', $data->getNumber());
    }

    public function saveInvoiceConfigurationDataProvider(): array
    {
        return [
            ['orderId' => self::TEST_ORDER_ID],
            ['orderId' => self::TEST_NOT_EXISTING_ORDER_ID],
        ];
    }

    protected function getSut(): InvoiceConfigurationRepository
    {
        return $this->get(InvoiceConfigurationRepository::class);
    }
}
