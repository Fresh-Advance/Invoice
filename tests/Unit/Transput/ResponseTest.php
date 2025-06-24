<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\Transput;

use FreshAdvance\Invoice\Transput\ResponseProxy;
use OxidEsales\Eshop\Core\Utils;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    public function testSetHeaderProxied(): void
    {
        $testValue = 'someExampleHeader';

        $utilsMock = $this->createPartialMock(Utils::class, ['setHeader']);
        $utilsMock->expects($this->once())->method('setHeader')->with($testValue);

        $sut = new ResponseProxy($utilsMock);
        $sut->setHeader($testValue);
    }

    public function testShowMessageAndExitProxied(): void
    {
        $testValue = 'someExampleHeader';

        $utilsMock = $this->createPartialMock(Utils::class, ['showMessageAndExit']);
        $utilsMock->expects($this->once())->method('showMessageAndExit')->with($testValue);

        $sut = new ResponseProxy($utilsMock);
        $sut->showMessageAndExit($testValue);
    }
}
