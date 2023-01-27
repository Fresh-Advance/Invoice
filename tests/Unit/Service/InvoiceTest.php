<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\Service;

use FreshAdvance\Invoice\DataType\InvoiceConfiguration;
use FreshAdvance\Invoice\DataType\InvoiceConfigurationInterface;
use FreshAdvance\Invoice\Repository\Invoice as InvoiceRepository;
use FreshAdvance\Invoice\Service\Context;
use FreshAdvance\Invoice\Service\Invoice;
use FreshAdvance\Invoice\Service\ModuleSettings;
use FreshAdvance\Invoice\Service\Order;
use FreshAdvance\Invoice\Service\Shop;
use OxidEsales\Eshop\Application\Model\Order as OrderModel;
use OxidEsales\Eshop\Application\Model\Shop as ShopModel;
use OxidEsales\Eshop\Core\Config;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FreshAdvance\Invoice\Service\Invoice
 */
class InvoiceTest extends TestCase
{
    public function testGetInvoiceData(): void
    {
        $orderStub = $this->createConfiguredMock(OrderModel::class, [
            'getShopId' => 3,
            'getId' => 'someOrderId'
        ]);

        $orderServiceMock = $this->createPartialMock(Order::class, ['getOrder']);
        $orderServiceMock->method('getOrder')->with('someOrderId')->willReturn($orderStub);

        $shopStub = $this->createStub(ShopModel::class);
        $shopServiceMock = $this->createPartialMock(Shop::class, ['getShop']);
        $shopServiceMock->method('getShop')->with(3)->willReturn($shopStub);

        $shopConfigMock = $this->createPartialMock(Config::class, ['getShopConfVar']);
        $shopConfigMock->method('getShopConfVar')->with('sDefaultLang', 3)->willReturn(5);

        $invoiceConfigurationStub = $this->createStub(InvoiceConfigurationInterface::class);
        $repositoryMock = $this->createPartialMock(InvoiceRepository::class, ['getOrderInvoice']);
        $repositoryMock->method('getOrderInvoice')
            ->with('someOrderId')
            ->willReturn($invoiceConfigurationStub);

        $moduleSettingsStub = $this->createConfiguredMock(ModuleSettings::class, [
            'getFilePrefix' => 'prefix'
        ]);

        $sut = new Invoice(
            orderService: $orderServiceMock,
            shopService: $shopServiceMock,
            shopConfig: $shopConfigMock,
            moduleContext: $this->createConfiguredMock(
                Context::class,
                ['getInvoicesPath' => 'someRootPath']
            ),
            invoiceRepository: $repositoryMock,
            moduleSettings: $moduleSettingsStub
        );

        $result = $sut->getInvoiceDataByOrderId('someOrderId');

        $this->assertSame($orderStub, $result->getOrder());
        $this->assertSame($shopStub, $result->getShop());
        $this->assertSame(5, $result->getLanguageId());
        $this->assertSame('someRootPath/so/someOrderId.pdf', $result->getInvoicePath());
        $this->assertSame($invoiceConfigurationStub, $result->getInvoiceConfiguration());
    }

    public function testSaveOrderInvoiceData(): void
    {
        $configurationStub = $this->createStub(InvoiceConfigurationInterface::class);

        $repositoryMock = $this->createPartialMock(InvoiceRepository::class, ['saveInvoiceConfiguration']);
        $repositoryMock->expects($this->atLeastOnce())
            ->method('saveInvoiceConfiguration')
            ->with($configurationStub);

        $moduleSettingsStub = $this->createConfiguredMock(ModuleSettings::class, [
            'getFilePrefix' => 'prefix'
        ]);

        $sut = new Invoice(
            orderService: $this->createStub(Order::class),
            shopService: $this->createStub(Shop::class),
            shopConfig: $this->createStub(Config::class),
            moduleContext: $this->createStub(Context::class),
            invoiceRepository: $repositoryMock,
            moduleSettings: $moduleSettingsStub
        );

        $sut->saveOrderInvoiceData($configurationStub);
    }

    public function testGetInvoiceFileName(): void
    {
        $configurationStub = $this->createConfiguredMock(InvoiceConfiguration::class, [
            'getNumber' => 'someNumber'
        ]);

        $moduleSettingsStub = $this->createConfiguredMock(ModuleSettings::class, [
            'getFilePrefix' => 'prefix-'
        ]);

        $sut = new Invoice(
            orderService: $this->createStub(Order::class),
            shopService: $this->createStub(Shop::class),
            shopConfig: $this->createStub(Config::class),
            moduleContext: $this->createStub(Context::class),
            invoiceRepository: $this->createStub(InvoiceRepository::class),
            moduleSettings: $moduleSettingsStub
        );

        $this->assertSame("prefix-someNumber.pdf", $sut->getInvoiceFileName($configurationStub));
    }
}
