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

namespace CoiSA\Proxy\Test\Functional\Container\ServiceProvider;

use CoiSA\Proxy\Container\ServiceProvider\ProxyServiceProvider;
use CoiSA\Proxy\Http\Message\ProxyUriFactory;
use CoiSA\Proxy\Test\Functional\Container\ContainerFactory;
use PHPUnit\Framework\TestCase;

/**
 * Class ProxyServiceProviderTest
 *
 * @package CoiSA\Proxy\Test\Functional\Container\ServiceProvider
 */
final class ProxyServiceProviderTest extends TestCase
{
    private $proxyServiceProvider;

    public function setUp(): void
    {
        $this->proxyServiceProvider = new ProxyServiceProvider();
    }

    public function testFactoryImplementInstanceOf(): void
    {
        $container = ContainerFactory::createCoisaContainer();

        $proxyServiceProvider = new ProxyServiceProvider();

        foreach ($proxyServiceProvider->getFactories() as $instanceOf => $factory) {
            if ($instanceOf === 'config') {
                $config = $factory($container);

                /** @var ProxyUriFactory $proxyUriFactory */
                $proxyUriFactory = $container->get(ProxyUriFactory::class);

                $this->assertIsArray($config);
                $this->assertArrayHasKey(ProxyUriFactory::class, $config);
                $this->assertEquals($config[ProxyUriFactory::class], (string) $proxyUriFactory->getTargetUri());

                continue;
            }

            $this->assertInstanceOf($instanceOf, $factory($container));
        }
    }
}
