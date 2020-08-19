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

namespace CoiSA\Proxy\Http\Server\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Class ProxyMiddleware
 *
 * @package CoiSA\Proxy\Http\Server\Middleware
 */
final class ProxyMiddleware implements MiddlewareInterface
{
    /**
     * @var UriFactoryInterface
     */
    private $uriFactory;

    /**
     * ProxyMiddleware constructor.
     *
     * @param UriFactoryInterface $uriFactory
     */
    public function __construct(UriFactoryInterface $uriFactory)
    {
        $this->uriFactory = $uriFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $uri          = $request->getUri();
        $proxyUri     = $this->uriFactory->createUri((string) $uri);
        $proxyRequest = $request->withUri($proxyUri);

        return $handler->handle($proxyRequest);
    }
}
