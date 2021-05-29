<?php

namespace Prokl\ContainerLocatorBundle\Services;

use Prokl\ContainerLocatorBundle\ContainerLocatorBundle;
use RuntimeException;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ContainerLocator
 * @package Prokl\ContainerLocatorBundle\Services
 *
 * @since 28.05.2021
 */
final class ContainerLocator
{
    /**
     * @var array $bundlesClass Классы бандлов.
     */
    private $bundlesClass = [];

    /**
     * ContainerLocator constructor.
     *
     * @param string $config Путь к конфигу бандлов или классу бандла.
     */
    public function __construct(string $config)
    {
        if (class_exists($config)) {
            $this->bundlesClass[] = $config;
            return;
        }

        /** @psalm-suppress MixedOperand */
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . $config)) {
            /** @psalm-suppress MixedOperand */
            $bundles = require $_SERVER['DOCUMENT_ROOT'] . $config;

            if (is_array($bundles)) {
                $this->bundlesClass = array_keys($bundles);
            }
        }
    }

    /**
     * Статический конструктор. Из конфигурации бандлов.
     *
     * @param string $configFile Конфиг бандлов.
     *
     * @return ContainerInterface
     */
    public static function instanceFromBundles(
        string $configFile = '/config/standalone_bundles.php'
    ) : ContainerInterface {
        $self = new self($configFile);

        return $self->container();
    }

    /**
     * Статический конструктор. Из класса бандла.
     *
     * @return ContainerInterface
     * @throws RuntimeException Когда контейнер не инициализирован.
     */
    public static function instance() : ContainerInterface
    {
        $self = new self(ContainerLocatorBundle::class);

        return $self->container();
    }

    /**
     * Экземпляр контейнера.
     *
     * @return ContainerInterface
     * @throws RuntimeException Когда контейнер не инициализирован.
     */
    public function container() : ContainerInterface
    {
        /** @var string $bundleClass */
        foreach ($this->bundlesClass as $bundleClass) {
            if (class_exists($bundleClass) && method_exists($bundleClass, 'getContainer')) {
                /** @var ContainerInterface|null $container */
                $container =  $bundleClass::getContainer();

                if ($container === null) {
                    throw new RuntimeException(
                        'Container not initialized.'
                    );
                }

                return $container;
            }
        }

        throw new RuntimeException(
            'Bundle with container getter not found.'
        );
    }
}