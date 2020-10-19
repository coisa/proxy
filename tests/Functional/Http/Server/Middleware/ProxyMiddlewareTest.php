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

namespace CoiSA\Proxy\Test\Functional\Http\Server\RequestHandler;

use CoiSA\Proxy\Http\Server\Middleware\ProxyMiddleware;
use CoiSA\Proxy\Http\Server\RequestHandler\ProxyRequestHandler;
use CoiSA\Proxy\Test\Functional\Container\ContainerFactory;
use Laminas\Diactoros\ServerRequestFactory;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class ProxyMiddlewareTest.
 *
 * @package CoiSA\Proxy\Test\Functional\Http\Server\RequestHandler
 */
final class ProxyMiddlewareTest extends TestCase
{
    /**
     * @var ProxyMiddleware
     */
    private $proxyMiddleware;

    /**
     * @var ProxyRequestHandler
     */
    private $proxyRequestHandler;

    /**
     * @var ServerRequestInterface
     */
    private $serverRequest;

    public function setUp(): void
    {
        $container = ContainerFactory::createContainer();

        $this->proxyMiddleware     = $container->get(ProxyMiddleware::class);
        $this->proxyRequestHandler = $container->get(ProxyRequestHandler::class);
        $this->serverRequest       = ServerRequestFactory::fromGlobals();

        $this->serverRequest = $this->serverRequest->withUri($this->serverRequest->getUri()->withPath(''));
    }

    public function testProcessWillReturnResponse(): void
    {
        $response = $this->proxyMiddleware->process($this->serverRequest, $this->proxyRequestHandler);
        $this->assertInstanceOf(ResponseInterface::class, $response);
    }
}
