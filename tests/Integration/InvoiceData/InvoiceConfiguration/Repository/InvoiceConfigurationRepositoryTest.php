<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Integration\Invoice\InvoiceConfiguration\Repository;

use FreshAdvance\Invoice\InvoiceData\InvoiceConfiguration\DataType\InvoiceConfiguration;
use FreshAdvance\Invoice\InvoiceData\InvoiceConfiguration\DataType\InvoiceConfigurationInterface;
use FreshAdvance\Invoice\InvoiceData\InvoiceConfiguration\Repository\InvoiceConfigurationRepository;
use FreshAdvance\Invoice\InvoiceData\InvoiceConfiguration\Repository\InvoiceConfigurationRepositoryInterface;
use OxidEsales\EshopCommunity\Tests\Integration\IntegrationTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;

#[CoversClass(InvoiceConfigurationRepository::class)]
class InvoiceConfigurationRepositoryTest extends IntegrationTestCase
{
    protected const TEST_ORDER_ID = 'someTestOrderId';
    protected const TEST_NOT_EXISTING_ORDER_ID = 'someNotExistingOrderId';

    public function setUp(): void
    {
        parent::setUp();

        $sut = $this->getSut();
        $sut->save(
            new InvoiceConfiguration(
                orderId: self::TEST_ORDER_ID,
                signer: 'someSigner',
                date: 'someDate',
                number: 'someNumber'
            )
        );
    }

    public function testExistingOrderInvoice(): void
    {
        $sut = $this->getSut();

        $data = $sut->getByOrderId(self::TEST_ORDER_ID);
        $this->assertInstanceOf(InvoiceConfigurationInterface::class, $data);

        $this->assertSame(self::TEST_ORDER_ID, $data->getOrderId());
        $this->assertSame('someSigner', $data->getSigner());
        $this->assertSame('someDate', $data->getDate());
        $this->assertSame('someNumber', $data->getNumber());
    }

    #[DataProvider('saveInvoiceConfigurationDataProvider')]
    public function testSaveInvoiceConfiguration(string $orderId): void
    {
        $sut = $this->getSut();
        $sut->save(
            new InvoiceConfiguration(
                orderId: $orderId,
                signer: 'someOtherSigner',
                date: 'someOtherDate',
                number: 'someOtherNumber'
            )
        );

        $data = $sut->getByOrderId($orderId);
        $this->assertInstanceOf(InvoiceConfigurationInterface::class, $data);

        $this->assertSame($orderId, $data->getOrderId());
        $this->assertSame('someOtherSigner', $data->getSigner());
        $this->assertSame('someOtherDate', $data->getDate());
        $this->assertSame('someOtherNumber', $data->getNumber());
    }

    public static function saveInvoiceConfigurationDataProvider(): array
    {
        return [
            ['orderId' => self::TEST_ORDER_ID],
            ['orderId' => self::TEST_NOT_EXISTING_ORDER_ID],
        ];
    }

    protected function getSut(): InvoiceConfigurationRepositoryInterface
    {
        return $this->get(InvoiceConfigurationRepositoryInterface::class);
    }
}
