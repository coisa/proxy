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
use CoiSA\ServiceProvider\LaminasConfigServiceProvider;
use CoiSA\ServiceProvider\ServiceProvider;

/**
 * Class ProxyServiceProvider
 *
 * @package CoiSA\Proxy\Container\ServiceProvider
 */
final class ProxyServiceProvider extends ServiceProvider
{
    /**
     * ProxyServiceProvider constructor.
     *
     * @param null|string $proxyUrl
     */
    public function __construct(string $proxyUrl = null)
    {
        $configProvider  = new ProxyConfigProvider($proxyUrl);
        $serviceProvider = new LaminasConfigServiceProvider($configProvider());

        $this->factories  = $serviceProvider->getFactories();
        $this->extensions = $serviceProvider->getExtensions();
    }
}
