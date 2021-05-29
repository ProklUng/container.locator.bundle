<?php

namespace Prokl\ContainerLocatorBundle\Tests\Fixtures;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Class AppKernelTesting
 * Тестовый Kernel
 * @package Prokl\ContainerLocatorBundle\Tests\Fixtures
 */
class AppKernelTesting extends Kernel
{
    /**
     * @inheritDoc
     */
    public function registerBundles()
    {
    }

    /**
     * @inheritDoc
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
    }
}