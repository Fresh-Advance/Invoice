<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\Service;

use FreshAdvance\Invoice\Service\Form;
use OxidEsales\Eshop\Core\Request;
use PHPUnit\Framework\TestCase;

/**
 * @covers \FreshAdvance\Invoice\Service\Form
 */
class FormTest extends TestCase
{
    public function testGetConfigurationFromRequest(): void
    {
        $formData = [
            'order_id' => 'someOrderId',
            'date' => 'someDate',
            'number' => 'someNumber',
            'signer' => 'someSigner',
        ];

        $requestStub = $this->createConfiguredMock(Request::class, [
            'getRequestParameter' => $this->returnValueMap([
                ['invoice', null, $formData]
            ])
        ]);

        $sut = new Form($requestStub);
        $configuration = $sut->getConfigurationFromRequest();

        $this->assertSame('someOrderId', $configuration->getOrderId());
        $this->assertSame('someDate', $configuration->getDate());
        $this->assertSame('someNumber', $configuration->getNumber());
        $this->assertSame('someSigner', $configuration->getSigner());
    }
}
