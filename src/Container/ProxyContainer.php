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

namespace CoiSA\Proxy\Container;

use CoiSA\Container\Container;
use CoiSA\Proxy\Container\ServiceProvider\ProxyServiceProvider;
use Psr\Container\ContainerInterface;

/**
 * Class ProxyContainer.
 *
 * @package CoiSA\Proxy\Container
 */
final class ProxyContainer implements ContainerInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * ProxyContainer constructor.
     *
     * @param string $proxyUrl
     */
    public function __construct(string $proxyUrl)
    {
        $proxyServiceProvider = new ProxyServiceProvider($proxyUrl);
        $this->container      = new Container();

        $this->container->register($proxyServiceProvider);
    }

    /**
     * {@inheritdoc}
     */
    public function has($id)
    {
        return $this->container->has($id);
    }

    /**
     * {@inheritdoc}
     */
    public function get($id)
    {
        return $this->container->get($id);
    }
}
