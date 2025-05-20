<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

namespace FreshAdvance\Invoice\ElectronicInvoice\ZUGFeRD\Factory;

use horstoeko\zugferd\ZugferdDocumentBuilder;
use horstoeko\zugferd\ZugferdProfiles;

class ZugferdXmlBuilderFactory implements ZugferdXmlBuilderFactoryInterface
{
    public function createBuilder(): ZugferdDocumentBuilder
    {
        return ZugferdDocumentBuilder::createNew(
            profileId: ZugferdProfiles::PROFILE_EN16931
        );
    }
}
