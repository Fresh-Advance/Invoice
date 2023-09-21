<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Transition\Core;

use OxidEsales\Eshop\Core\Utils;

class UtilsProxy implements UtilsInterface
{
    public function __construct(
        private Utils $utils
    ) {
    }

    public function setHeader(string $header): void
    {
        $this->utils->setHeader($header);
    }

    public function showMessageAndExit(string $message): void
    {
        $this->utils->showMessageAndExit($message);
    }
}
