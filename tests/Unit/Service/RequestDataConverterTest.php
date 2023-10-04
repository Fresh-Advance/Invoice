<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\Service;

use FreshAdvance\Invoice\Service\RequestDataConverter;
use FreshAdvance\Invoice\Transput\RequestInterface;
use OxidEsales\Eshop\Core\Request;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FreshAdvance\Invoice\Service\RequestDataConverter
 */
class RequestDataConverterTest extends TestCase
{
    public function testGetConfigurationFromRequest(): void
    {
        $formData = [
            'order_id' => 'someOrderId',
            'date' => 'someDate',
            'number' => 'someNumber',
            'signer' => 'someSigner',
        ];

        $requestStub = $this->createConfiguredMock(RequestInterface::class, [
            'getRequestParameter' => $this->returnValueMap([
                [RequestDataConverter::INVOICES_FORM_ARRAY, null, $formData]
            ])
        ]);

        $sut = new RequestDataConverter($requestStub);
        $configuration = $sut->getConfigurationFromRequest();

        $this->assertSame('someOrderId', $configuration->getOrderId());
        $this->assertSame('someDate', $configuration->getDate());
        $this->assertSame('someNumber', $configuration->getNumber());
        $this->assertSame('someSigner', $configuration->getSigner());
    }
}
