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

namespace CoiSA\Proxy\Http\Client;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;

/**
 * Class ProxyClient
 *
 * @package CoiSA\Proxy\Client
 */
final class ProxyClient implements ClientInterface
{
    /**
     * @var UriFactoryInterface
     */
    private $uriFactory;

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var StreamFactoryInterface
     */
    private $streamFactory;

    /**
     * ProxyClient constructor.
     *
     * @param UriFactoryInterface    $uriFactory
     * @param ClientInterface        $client
     * @param StreamFactoryInterface $streamFactory
     */
    public function __construct(
        UriFactoryInterface $uriFactory,
        ClientInterface $client,
        StreamFactoryInterface $streamFactory
    ) {
        $this->uriFactory    = $uriFactory;
        $this->client        = $client;
        $this->streamFactory = $streamFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $proxyUri     = $this->uriFactory->createUri((string) $request->getUri());
        $proxyRequest = $request->withUri($proxyUri);

        $response     = $this->client->sendRequest($proxyRequest);
        $detachedBody = (string) $response->getBody()->detach();

        if (null !== $detachedBody) {
            $stream   = $this->streamFactory->createStream($detachedBody);
            $response = $response->withBody($stream);
        }

        return $response;
    }
}
