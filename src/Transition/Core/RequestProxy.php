<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace FreshAdvance\Invoice\Transition\Core;

use OxidEsales\Eshop\Core\Request;

class RequestProxy implements RequestInterface
{
    public function __construct(
        private Request $request
    ) {
    }

    public function getRequestEscapedParameter(string $requestParam): mixed
    {
        return $this->request->getRequestEscapedParameter($requestParam);
    }

    public function getRequestParameter(string $requestParam): mixed
    {
        return $this->request->getRequestParameter($requestParam);
    }
}
