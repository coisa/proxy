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

namespace CoiSA\Proxy\Test\Functional\Container;

use CoiSA\Container\Container;
use CoiSA\Proxy\Container\ConfigProvider\ProxyConfigProvider;
use CoiSA\Proxy\Container\ServiceProvider\ProxyServiceProvider;
use CoiSA\ServiceProvider\Factory\AliasFactory;
use CoiSA\ServiceProvider\Factory\InvokableFactory;
use CoiSA\ServiceProvider\LaminasConfigServiceProvider;
use CoiSA\ServiceProvider\ServiceProviderAggregator;
use Http\Client\Curl\Client;
use Interop\Container\ServiceProviderInterface;
use Laminas\Diactoros\ConfigProvider;
use Laminas\ServiceManager\ServiceManager;
use Psr\Container\ContainerInterface;
use Psr\Http\Client\ClientInterface;

/**
 * Class ContainerFactory.
 *
 * @package CoiSA\Proxy\Test\Functional\Container
 */
abstract class ContainerFactory
{
    /**
     * @const string
     */
    const DEFAULT_PROXY_URI = 'http://google.com/';

    /**
     * @return ContainerInterface
     */
    public static function createContainer(): ContainerInterface
    {
        return self::createServiceManager();
    }

    /**
     * @return Container
     */
    public static function createServiceProviderContainer(): Container
    {
        $serviceProvider = self::createServiceProvider();

        $container = new Container();
        $container->register($serviceProvider);

        return $container;
    }

    /**
     * @return ServiceManager
     */
    private static function createServiceManager(): ServiceManager
    {
        $proxyConfigProvider     = new ProxyConfigProvider(self::DEFAULT_PROXY_URI);
        $diactorosConfigProvider = new ConfigProvider();

        $config = \array_merge_recursive(
            $proxyConfigProvider(),
            $diactorosConfigProvider()
        );

        $serviceManager = new ServiceManager($config['dependencies']);
        $serviceManager->setService('config', $config);
        $serviceManager->setService(ClientInterface::class, new Client());

        return $serviceManager;
    }

    /**
     * @return ServiceProviderInterface
     */
    private static function createServiceProvider(): ServiceProviderInterface
    {
        $proxyServiceProvider = new ProxyServiceProvider(self::DEFAULT_PROXY_URI);
        $proxyServiceProvider->setFactory(Client::class, new InvokableFactory(Client::class));
        $proxyServiceProvider->setFactory(ClientInterface::class, new AliasFactory(Client::class));

        $diactorosConfigProvider      = new ConfigProvider();
        $laminasConfigServiceProvider = new LaminasConfigServiceProvider($diactorosConfigProvider());

        $serviceProviderAggregator = new ServiceProviderAggregator();
        $serviceProviderAggregator->append($proxyServiceProvider);
        $serviceProviderAggregator->append($laminasConfigServiceProvider);

        return $serviceProviderAggregator;
    }
}
