<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\Invoice\Service;

use FreshAdvance\Invoice\InvoiceData\DataType\InvoiceDataInterface;
use FreshAdvance\Invoice\InvoiceData\InvoiceConfiguration\DataType\InvoiceConfigurationInterface;
use FreshAdvance\Invoice\InvoiceData\InvoiceConfiguration\Repository\InvoiceConfigurationRepositoryInterface;
use FreshAdvance\Invoice\InvoiceData\Service\Invoice;
use FreshAdvance\Invoice\Order\Repository\OrderRepositoryInterface;
use FreshAdvance\Invoice\Pdf\Service\FilenameCalculatorInterface;
use FreshAdvance\Invoice\Repository\ShopRepositoryInterface;
use FreshAdvance\Invoice\Settings\ConfigInterface;
use FreshAdvance\Invoice\Settings\ContextInterface;
use FreshAdvance\Invoice\Settings\ModuleSettings;
use FreshAdvance\Invoice\Settings\ModuleSettingsInterface;
use OxidEsales\Eshop\Application\Model\Order as OrderModel;
use OxidEsales\Eshop\Application\Model\Shop as ShopModel;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FreshAdvance\Invoice\InvoiceData\Service\Invoice
 */
class InvoiceTest extends TestCase
{
    public function testGetInvoiceData(): void
    {
        $orderStub = $this->createConfiguredMock(OrderModel::class, [
            'getShopId' => 3,
            'getId' => 'someOrderId'
        ]);

        $orderServiceMock = $this->createMock(OrderRepositoryInterface::class);
        $orderServiceMock->method('getByOrderId')->with('someOrderId')->willReturn($orderStub);

        $shopStub = $this->createStub(ShopModel::class);
        $shopServiceMock = $this->createMock(ShopRepositoryInterface::class);
        $shopServiceMock->method('getByShopId')->with(3)->willReturn($shopStub);

        $shopConfigMock = $this->createMock(ConfigInterface::class);
        $shopConfigMock->method('getShopDefaultLanguageId')->with(3)->willReturn(5);

        $invoiceConfigurationStub = $this->createStub(InvoiceConfigurationInterface::class);
        $repositoryMock = $this->createMock(InvoiceConfigurationRepositoryInterface::class);
        $repositoryMock->method('getByOrderId')
            ->with('someOrderId')
            ->willReturn($invoiceConfigurationStub);

        $sut = $this->getSut(
            orderRepository: $orderServiceMock,
            shopService: $shopServiceMock,
            shopConfig: $shopConfigMock,
            moduleContext: $this->createConfiguredMock(
                \FreshAdvance\Invoice\Settings\Context::class,
                ['getInvoicesPath' => 'someRootPath']
            ),
            invoiceConfigRepo: $repositoryMock,
        );

        $result = $sut->getInvoiceDataByOrderId('someOrderId');

        $this->assertSame($orderStub, $result->getOrder());
        $this->assertSame($shopStub, $result->getShop());
        $this->assertSame(5, $result->getLanguageId());
        $this->assertSame('someRootPath/so/someOrderId.pdf', $result->getInvoicePath());
        $this->assertSame($invoiceConfigurationStub, $result->getInvoiceConfiguration());
    }

    protected function getSut(
        OrderRepositoryInterface $orderRepository = null,
        ShopRepositoryInterface $shopService = null,
        ConfigInterface $shopConfig = null,
        ContextInterface $moduleContext = null,
        InvoiceConfigurationRepositoryInterface $invoiceConfigRepo = null,
        ModuleSettingsInterface $moduleSettings = null,
        FilenameCalculatorInterface $filenameCalculator = null,
    ): Invoice {
        return new \FreshAdvance\Invoice\InvoiceData\Service\Invoice(
            orderRepository: $orderRepository ?? $this->createStub(OrderRepositoryInterface::class),
            shopService: $shopService ?? $this->createStub(ShopRepositoryInterface::class),
            shopConfig: $shopConfig ?? $this->createStub(ConfigInterface::class),
            moduleContext: $moduleContext ?? $this->createStub(ContextInterface::class),
            invoiceConfigRepo: $invoiceConfigRepo ?? $this->createStub(InvoiceConfigurationRepositoryInterface::class),
            moduleSettings: $moduleSettings ?? $this->createStub(ModuleSettingsInterface::class),
            filenameCalculator: $filenameCalculator ?? $this->createStub(FilenameCalculatorInterface::class),
        );
    }
}
