<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace Exception;

use FreshAdvance\Invoice\Exception\InvoiceConfigurationNotFound;
use PHPUnit\Framework\TestCase;

class InvoiceConfigurationNotFoundTest extends TestCase
{
    public function testException(): void
    {
        $sut = new InvoiceConfigurationNotFound();
        $this->assertInstanceOf(\Exception::class, $sut);
    }
}
