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

namespace CoiSA\Proxy\Container\ServiceProvider;

use CoiSA\Proxy\Container\ConfigProvider\ProxyConfigProvider;
use CoiSA\Proxy\Http\Message\ProxyUriFactory;
use Interop\Container\ServiceProviderInterface;
use Psr\Container\ContainerInterface;

/**
 * Class ProxyServiceProvider
 *
 * @package CoiSA\Proxy\Container\ServiceProvider
 */
final class ProxyServiceProvider implements ServiceProviderInterface
{
    /**
     * @var null|string
     */
    private $proxyUrl;

    /**
     * @var ProxyConfigProvider
     */
    private $configProvider;

    /**
     * ProxyServiceProvider constructor.
     *
     * @param null|string $proxyUrl
     */
    public function __construct(string $proxyUrl = null)
    {
        $this->proxyUrl       = $proxyUrl;
        $this->configProvider = new ProxyConfigProvider();
    }

    /**
     * {@inheritDoc}
     */
    public function getFactories()
    {
        $factories = $this->configProvider->getFactories();

        foreach ($factories as $class => $factory) {
            $factories[$class] = new $factory();
        }

        $factories['config'] = function () {
            return $this->configProvider->getConfig();
        };

        return $factories;
    }

    /**
     * {@inheritDoc}
     */
    public function getExtensions()
    {
        return [
            'config' => function (ContainerInterface $container, $previous = null) {
                $config = $previous ?? [];

                if ($this->proxyUrl) {
                    $config[ProxyUriFactory::class] = $this->proxyUrl;
                }

                return \array_merge(
                    $this->configProvider->getConfig(),
                    $config
                );
            },
        ];
    }
}
