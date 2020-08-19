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
use CoiSA\Container\Singleton\ContainerSingleton;
use CoiSA\Proxy\Container\ConfigProvider\ProxyConfigProvider;
use CoiSA\Proxy\Container\ServiceProvider\ProxyServiceProvider;
use CoiSA\Proxy\Http\Message\ProxyUriFactory;
use Http\Client\Curl\Client;
use Interop\Container\ServiceProviderInterface;
use Laminas\Diactoros\ConfigProvider;
use Laminas\ServiceManager\ServiceManager;
use Psr\Container\ContainerInterface;
use Psr\Http\Client\ClientInterface;

/**
 * Class ContainerFactory
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
    public static function createCoisaContainer(): Container
    {
        ContainerSingleton::register(new ProxyServiceProvider(self::DEFAULT_PROXY_URI));
        ContainerSingleton::register(self::createServiceProvider());

        return ContainerSingleton::getInstance();
    }

    /**
     * @return ServiceManager
     */
    private static function createServiceManager(): ServiceManager
    {
        $configProvider = new ProxyConfigProvider(self::DEFAULT_PROXY_URI);

        $config                         = $configProvider();
        $config[ProxyUriFactory::class] = self::DEFAULT_PROXY_URI;

        $serviceManager = new ServiceManager();
        $serviceManager->configure($configProvider->getDependencies());
        $serviceManager->configure((new ConfigProvider())->getDependencies());

        $serviceManager->setService('config', $config);
        $serviceManager->setService(ClientInterface::class, new Client());

        return $serviceManager;
    }

    /**
     * @return ServiceProviderInterface
     */
    private static function createServiceProvider(): ServiceProviderInterface
    {
        return new class() implements ServiceProviderInterface {
            public function getFactories()
            {
                $factories = (new ConfigProvider())->getDependencies()['invokables'];

                foreach ($factories as $class => $factory) {
                    $factories[$class] = function () use ($factory) {
                        return new $factory();
                    };
                }

                $factories[ClientInterface::class] = function () {
                    return new Client();
                };

                return $factories;
            }

            public function getExtensions()
            {
                return [];
            }
        };
    }
}
