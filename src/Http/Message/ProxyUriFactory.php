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

namespace CoiSA\Proxy\Http\Message;

use Psr\Http\Message\UriFactoryInterface;
use Psr\Http\Message\UriInterface;

/**
 * Class ProxyUriFactory.
 *
 * @package CoiSA\Proxy\Http\Message
 */
final class ProxyUriFactory implements UriFactoryInterface
{
    /**
     * @var UriInterface
     */
    private $targetUri;

    /**
     * @var UriFactoryInterface
     */
    private $uriFactory;

    /**
     * ProxyUriFactory constructor.
     *
     * @param string|UriInterface $targetUri
     * @param UriFactoryInterface $uriFactory
     */
    public function __construct($targetUri, UriFactoryInterface $uriFactory)
    {
        $this->uriFactory = $uriFactory;
        $this->setTargetUri($targetUri);
    }

    /**
     * @param string|UriInterface $targetUri
     *
     * @return self
     */
    public function setTargetUri($targetUri): self
    {
        $this->targetUri = $this->uriFactory->createUri((string) $targetUri);

        return $this;
    }

    /**
     * @return UriInterface
     */
    public function getTargetUri(): UriInterface
    {
        return $this->targetUri;
    }

    /**
     * @param string $uri
     *
     * @return UriInterface
     */
    public function createUri(string $uri = ''): UriInterface
    {
        $target = $this->getTargetUri();

        $proxyUri = $this->uriFactory->createUri($uri)
            ->withScheme($target->getScheme())
            ->withHost($target->getHost())
            ->withPort($target->getPort());

        if ($this->targetUri->getPath()) {
            return $proxyUri->withPath(
                \str_replace('//', '/', $target->getPath() . '/' . $proxyUri->getPath())
            );
        }

        return $proxyUri;
    }
}
