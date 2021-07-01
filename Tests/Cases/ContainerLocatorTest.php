<?php

namespace Prokl\ContainerLocatorBundle\Tests\Cases;

use Exception;
use Prokl\ContainerLocatorBundle\ContainerLocatorBundle;
use Prokl\ContainerLocatorBundle\Services\ContainerLocator;
use Prokl\TestingTools\Base\BaseTestCase;
use Prokl\TestingTools\Tools\Container\BuildContainer;
use Psr\Container\ContainerInterface;

/**
 * Class ContainerLocatorTest
 * @package Prokl\ContainerLocatorBundle\Tests\Cases
 *
 * @since 01.07.2021
 */
class ContainerLocatorTest extends BaseTestCase
{
    /**
     * @inheritDoc
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->container = static::$testContainer = BuildContainer::getTestContainer(
            [
                'test_container.yaml'
            ],
            '/Tests/Fixtures',
        );

        parent::setUp();

        // Эмуляция инициализации бандла.
        $bundle = new ContainerLocatorBundle();
        $bundle->setContainer($this->container);
    }

    /**
     * @return void
     */
    public function testInstance() : void
    {
        $result = ContainerLocator::instance();

        $this->assertInstanceOf(ContainerInterface::class, $result);
    }

    /**
     * Из конфига бандлов.
     *
     * @return void
     */
    public function testInstanceFromBundles() : void
    {
        $result = ContainerLocator::instanceFromBundles('/Tests/Fixtures/bundles.php');

        $this->assertInstanceOf(ContainerInterface::class, $result);
        $this->assertTrue($result->has('kernel'));
    }

    /**
     * Из класса бандла.
     *
     * @return void
     */
    public function testInstanceFromBundlesClass() : void
    {
        $result = ContainerLocator::instanceFromBundles(ContainerLocatorBundle::class);

        $this->assertInstanceOf(ContainerInterface::class, $result);
        $this->assertTrue($result->has('kernel'));
    }

}