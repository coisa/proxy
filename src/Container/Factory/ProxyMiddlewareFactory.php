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
use CoiSA\Proxy\Http\Server\Middleware\ProxyMiddleware;
use Psr\Container\ContainerInterface;

/**
 * Class ProxyMiddlewareFactory
 *
 * @package CoiSA\Proxy\Container\Factory
 */
final class ProxyMiddlewareFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return ProxyMiddleware
     */
    public function __invoke(ContainerInterface $container): ProxyMiddleware
    {
        $uriFactory = $container->get(ProxyUriFactory::class);

        return new ProxyMiddleware($uriFactory);
    }
}
