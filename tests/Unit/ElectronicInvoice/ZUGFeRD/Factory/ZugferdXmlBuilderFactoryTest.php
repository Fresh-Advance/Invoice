<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Tests\Unit\ElectronicInvoice\ZUGFeRD\Factory;

use Codeception\PHPUnit\TestCase;
use FreshAdvance\Invoice\ElectronicInvoice\ZUGFeRD\Factory\ZugferdXmlBuilderFactory;
use horstoeko\zugferd\ZugferdDocumentBuilder;
use horstoeko\zugferd\ZugferdProfiles;
use PHPUnit\Framework\Attributes\Test;

class ZugferdXmlBuilderFactoryTest extends TestCase
{
    #[Test]
    public function createBuilderReturnsNewBuilder(): void
    {
        $sut = new ZugferdXmlBuilderFactory();

        $builder1 = $sut->createBuilder();
        $builder2 = $sut->createBuilder();

        $this->assertInstanceOf(ZugferdDocumentBuilder::class, $builder1);
        $this->assertInstanceOf(ZugferdDocumentBuilder::class, $builder2);

        $this->assertNotSame($builder1, $builder2);
    }

    #[Test]
    public function correctProfilePreconfigured(): void
    {
        $sut = new ZugferdXmlBuilderFactory();

        $builder = $sut->createBuilder();
        $this->assertSame(ZugferdProfiles::PROFILE_EN16931, $builder->getProfileId());
    }
}
