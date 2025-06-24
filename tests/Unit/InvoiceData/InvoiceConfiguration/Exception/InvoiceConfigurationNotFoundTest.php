<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\InvoiceData\InvoiceConfiguration\Exception;

use FreshAdvance\Invoice\InvoiceData\InvoiceConfiguration\Exception\InvoiceConfigurationNotFound;
use PHPUnit\Framework\TestCase;

class InvoiceConfigurationNotFoundTest extends TestCase
{
    public function testException(): void
    {
        $sut = new InvoiceConfigurationNotFound();
        $this->assertInstanceOf(\Throwable::class, $sut);
    }
}
