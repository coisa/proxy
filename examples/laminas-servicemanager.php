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
use Laminas\ServiceManager\ServiceManager;

$configProvider = new ProxyConfigProvider();

$config                         = $configProvider();
$config[ProxyUriFactory::class] = 'https://google.com/';

$serviceManager = new ServiceManager();
$serviceManager->configure($configProvider->getDependencies());
$serviceManager->setService('config', $config);

return $serviceManager;
