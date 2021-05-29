<?php

namespace Prokl\ContainerLocatorBundle\Tests\Cases;

use Exception;
use Prokl\ContainerLocatorBundle\ContainerLocatorBundle;
use Prokl\ContainerLocatorBundle\Services\ContainerHelper;
use Prokl\TestingTools\Base\BaseTestCase;
use Prokl\TestingTools\Tools\Container\BuildContainer;
use ReflectionException;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class ContainerHelperTest
 * @package Prokl\ContainerLocatorBundle\Tests\Cases
 *
 * @since 29.05.2021
 */
class ContainerHelperTest extends BaseTestCase
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
    public function testKernel() : void
    {
        $result = ContainerHelper::kernel();

        $this->assertInstanceOf(KernelInterface::class, $result);
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testKernelParameters() : void
    {
        $result = ContainerHelper::kernelParameters();

        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('kernel.project_dir', $result, 'Нет ключа kernel.project_dir');
    }

    /**
     * @return void
     * @throws ReflectionException
     */
    public function testKernelParameter() : void
    {
        $result = ContainerHelper::parameter('kernel.project_dir');

        $this->assertNotEmpty($result);
    }
}