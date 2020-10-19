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

namespace CoiSA\Proxy\Container\ConfigProvider;

use CoiSA\Proxy\Container\Factory;
use CoiSA\Proxy\Http\Client\ProxyClient;
use CoiSA\Proxy\Http\Message\ProxyUriFactory;
use CoiSA\Proxy\Http\Server\Middleware\ProxyMiddleware;
use CoiSA\Proxy\Http\Server\RequestHandler\ProxyRequestHandler;

/**
 * Class ProxyConfigProvider.
 *
 * @package CoiSA\Proxy\Container\ConfigProvider
 */
final class ProxyConfigProvider
{
    /**
     * @var string
     */
    private $proxyUrl;

    /**
     * ProxyConfigProvider constructor.
     *
     * @param null|string $proxyUrl
     */
    public function __construct(string $proxyUrl = null)
    {
        $this->proxyUrl = $proxyUrl ?: '';
    }

    /**
     * @return array
     */
    public function __invoke(): array
    {
        return \array_merge($this->getConfig(), [
            'dependencies' => $this->getDependencies(),
        ]);
    }

    /**
     * @return string[]
     */
    public function getConfig(): array
    {
        return [
            ProxyUriFactory::class => $this->proxyUrl,
        ];
    }

    /**
     * @return string[]
     */
    public function getDependencies(): array
    {
        return [
            'services'  => $this->getServices(),
            'factories' => $this->getFactories(),
        ];
    }

    /**
     * @return object[]
     */
    public function getServices(): array
    {
        return [
            self::class => $this,
        ];
    }

    /**
     * @return string[]
     */
    public function getFactories(): array
    {
        return [
            ProxyClient::class         => Factory\ProxyClientFactory::class,
            ProxyMiddleware::class     => Factory\ProxyMiddlewareFactory::class,
            ProxyRequestHandler::class => Factory\ProxyRequestHandlerFactory::class,
            ProxyUriFactory::class     => Factory\ProxyUriFactoryFactory::class,
        ];
    }
}
