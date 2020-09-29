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

namespace CoiSA\Proxy\Container\Factory;

use CoiSA\Proxy\Http\Message\ProxyUriFactory;
use CoiSA\ServiceProvider\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\UriFactoryInterface;

/**
 * Class ProxyUriFactoryFactory
 *
 * @package CoiSA\Proxy\Container\Factory
 */
final class ProxyUriFactoryFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     *
     * @return ProxyUriFactory
     */
    public function __invoke(ContainerInterface $container): ProxyUriFactory
    {
        $config     = $container->get('config');
        $uriFactory = $container->get(UriFactoryInterface::class);
        $uri        = $uriFactory->createUri((string) $config[ProxyUriFactory::class]);

        return new ProxyUriFactory($uri, $uriFactory);
    }
}
