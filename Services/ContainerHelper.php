<?php

namespace Prokl\ContainerLocatorBundle\Services;

use ReflectionException;
use ReflectionMethod;
use RuntimeException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Twig\Environment;

/**
 * Class ContainerHelper
 * @package Prokl\ContainerLocatorBundle\Services
 *
 * @since 29.05.2021
 */
final class ContainerHelper
{
    /**
     * @var ContainerInterface|null $container Контейнер.
     */
    private static $container;

    /**
     * @var mixed $kernel
     */
    private static $kernel;

    /**
     * Kernel.
     *
     * @return mixed
     * @throws RuntimeException Когда kernel не найден в контейнере или контейнер не
     * инициализирован.
     */
    public static function kernel()
    {
        if (self::$kernel !== null) {
            return self::$kernel;
        }

        self::$kernel = self::service('kernel');

        return self::$kernel;
    }

    /**
     * Kernel. Параметры приложения
     *
     * @return array
     * @throws RuntimeException    Когда kernel не найден в контейнере или контейнер не инициализирован.
     * @throws ReflectionException Ошибки рефлексии.
     */
    public static function kernelParameters() : array
    {
        $kernel = self::kernel();

        // Частная реализация. У меня getKernelParameters - public
        // В "оригинале" - protected.
        $reflection = new ReflectionMethod($kernel, 'getKernelParameters');
        if ($reflection->isPublic()) {
            return $kernel->getKernelParameters();
        }

        $reflection->setAccessible(true);

        return $reflection->invokeArgs($kernel, []);
    }

    /**
     * Kernel. Параметр.
     *
     * @param string $param Параметр.
     *
     * @return mixed
     * @throws RuntimeException    Когда kernel не найден в контейнере или контейнер не инициализирован.
     * @throws ReflectionException Ошибки рефлексии.
     *
     */
    public static function parameter(string $param)
    {
        $params = self::kernelParameters();

        return $params[$param];
    }

    /**
     * Twig.
     *
     * @return Environment
     * @throws RuntimeException Когда контейнер не инициализирован или не найден сервис twig.instance.
     */
    public static function twig() : Environment
    {
        return self::service('twig.instance');
    }

    /**
     * Session.
     *
     * @return Session
     * @throws RuntimeException Когда контейнер не инициализирован или не найден сервис session.
     */
    public static function session() : Session
    {
        return self::service('session');
    }

    /**
     * Logger.
     *
     * @return object
     * @throws RuntimeException Когда контейнер не инициализирован или не найден сервис public_logger.
     */
    public static function logger()
    {
        return self::service('public_logger');
    }

    /**
     * Заполучить контейнер.
     *
     * @return void
     */
    private static function getContainer() : void
    {
        if (self::$container === null) {
            self::$container = ContainerLocator::instance();
        }
    }

    /**
     * Сервис.
     *
     * @param string $serviceId ID сервиса.
     *
     * @return mixed
     */
    private static function service(string $serviceId)
    {
        self::getContainer();
        /** @psalm-suppress PossiblyNullReference */
        if (!self::$container->has($serviceId)) {
            throw new RuntimeException('Service ' . $serviceId . ' not exist.');
        }

        return self::$container->get($serviceId);
    }
}
