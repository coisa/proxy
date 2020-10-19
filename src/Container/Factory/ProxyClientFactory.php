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

use CoiSA\Proxy\Http\Client\ProxyClient;
use CoiSA\Proxy\Http\Message\ProxyUriFactory;
use CoiSA\ServiceProvider\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\StreamFactoryInterface;

/**
 * Class ProxyClientFactory.
 *
 * @package CoiSA\Proxy\Container\Factory
 */
final class ProxyClientFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     *
     * @return ProxyClient
     */
    public function __invoke(ContainerInterface $container): ProxyClient
    {
        $uriFactory    = $container->get(ProxyUriFactory::class);
        $client        = $container->get(ClientInterface::class);
        $streamFactory = $container->get(StreamFactoryInterface::class);

        return new ProxyClient($uriFactory, $client, $streamFactory);
    }
}
