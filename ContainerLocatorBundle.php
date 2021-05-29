<?php

namespace Prokl\ContainerLocatorBundle;

use Prokl\ContainerLocatorBundle\DependencyInjection\ContainerLocatorExtension;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class ContainerLocatorBundle
 * @package Prokl\ContainerLocatorBundle
 *
 * @since 29.05.2021
 */
final class ContainerLocatorBundle extends Bundle
{
    /**
     * @var ContainerInterface $container Копия контейнера.
     */
    private static $containerCopy;

    /**
     * @inheritDoc
     */
    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        self::$containerCopy = $container;
    }

   /**
   * @inheritDoc
   */
    public function getContainerExtension()
    {
        if ($this->extension === null) {
            $this->extension = new ContainerLocatorExtension();
        }

        return $this->extension;
    }

    /**
     * Экземпляр контейнера.
     *
     * @return ContainerInterface
     */
    public static function getContainer()
    {
        return self::$containerCopy;
    }
}
