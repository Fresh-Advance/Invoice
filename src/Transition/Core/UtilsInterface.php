<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Transition\Core;

interface UtilsInterface
{
    public function setHeader(string $header): void;

    public function showMessageAndExit(string $message): void;
}
