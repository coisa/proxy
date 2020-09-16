<?php

/**
 * This file is part of coisa/proxy.
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 *
 * @link      https://github.com/coisa/proxy
 * @copyright Copyright (c) 2020 Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 * @license   https://opensource.org/licenses/MIT MIT License
 */

declare(strict_types=1);

namespace CoiSA\Proxy\Test\Functional\Container\ServiceProvider;

use CoiSA\Proxy\Container\ServiceProvider\ProxyServiceProvider;
use CoiSA\Proxy\Http\Message\ProxyUriFactory;
use CoiSA\Proxy\Test\Functional\Container\ContainerFactory;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * Class ProxyServiceProviderTest
 *
 * @package CoiSA\Proxy\Test\Functional\Container\ServiceProvider
 */
final class ProxyServiceProviderTest extends TestCase
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var ProxyServiceProvider
     */
    private $proxyServiceProvider;

    public function setUp(): void
    {
        $this->container            = ContainerFactory::createServiceProviderContainer();
        $this->proxyServiceProvider = new ProxyServiceProvider();
    }

    public function testFactoryImplementInstanceOf(): void
    {
        foreach ($this->proxyServiceProvider->getFactories() as $instanceOf => $factory) {
            $object = $factory($this->container);

            $this->assertInstanceOf($instanceOf, $object);
        }
    }

    public function testConfigServiceWillHaveGivenTargetUri(): void
    {
        $config = $this->container->get('config');

        /** @var ProxyUriFactory $proxyUriFactory */
        $proxyUriFactory = $this->container->get(ProxyUriFactory::class);

        $this->assertIsArray($config);
        $this->assertArrayHasKey(ProxyUriFactory::class, $config);
        $this->assertEquals($config[ProxyUriFactory::class], (string) $proxyUriFactory->getTargetUri());
    }
}
