<?php

namespace Prokl\ContainerLocatorBundle\DependencyInjection;

use Exception;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Class ContainerLocatorExtension
 * @package Prokl\ContainerLocator\DependencyInjection
 *
 * @since 29.05.2021
 */
class ContainerLocatorExtension extends Extension
{
    /**
     * @inheritDoc
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container) : void
    {
    }

    /**
     * @inheritDoc
     */
    public function getAlias() : string
    {
        return 'container_locator';
    }
}
