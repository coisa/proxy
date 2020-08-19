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

use CoiSA\Proxy\Container\ConfigProvider\ProxyConfigProvider;
use CoiSA\Proxy\Http\Message\ProxyUriFactory;
use Http\Client\Curl\Client;
use Laminas\Diactoros\ConfigProvider;
use Laminas\ServiceManager\ServiceManager;
use Psr\Http\Client\ClientInterface;

// Initialize ProxyConfigProvider
$configProvider = new ProxyConfigProvider();
$config         = $configProvider();

// Add the redirect proxy base URL
$config[ProxyUriFactory::class] = 'https://google.com/';

// Initialize ServiceManager
$serviceManager = new ServiceManager();

// Add config to container
$serviceManager->setService('config', $config);

// Configure CoiSA\Proxy services
$serviceManager->configure($configProvider->getDependencies());

// Configure PSR-17 & PSR-18 services
$serviceManager->configure((new ConfigProvider())->getDependencies());
$serviceManager->setService(ClientInterface::class, new Client());

return $serviceManager;
