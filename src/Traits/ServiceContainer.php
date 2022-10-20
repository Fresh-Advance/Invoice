<?php

/**
 * Copyright © MB Arbatos Klubas. All rights reserved.
 * See LICENSE file for license details.
 */

namespace FreshAdvance\Invoice\Traits;

use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

trait ServiceContainer
{
    /**
     * @template T
     * @psalm-param class-string<T> $serviceName
     *
     * @return T
     *
     * @throws ServiceNotFoundException
     */
    protected function getServiceFromContainer(string $serviceName)
    {
        return ContainerFactory::getInstance()
            ->getContainer()
            ->get($serviceName);
    }
}