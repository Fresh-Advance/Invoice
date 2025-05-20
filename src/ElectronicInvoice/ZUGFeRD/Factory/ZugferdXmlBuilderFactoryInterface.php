<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

namespace FreshAdvance\Invoice\ElectronicInvoice\ZUGFeRD\Factory;

use horstoeko\zugferd\ZugferdDocumentBuilder;

interface ZugferdXmlBuilderFactoryInterface
{
    public function createBuilder(): ZugferdDocumentBuilder;
}
