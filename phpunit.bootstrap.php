<?php

declare(strict_types=1);

use Medas\ConfigManager\ConfigManagerPackage;
use Medas\ConfigOptions\ConfigOptionsPackage;
use Medas\Core\Interfaces\ConfigManager;
use Medas\EntityGenerator\EntityGeneratorPackage;
use Medas\EntityGeneratorTest\MockUps\MockUpPackage;
use Medas\ObjectInstantiator\ObjectInstantiator;
use Medas\ServiceManager\{ServiceConfigBuilder, ServiceManager, ServiceManagerPackage};

chdir(__DIR__);

new ServiceManager(function (): ServiceConfigBuilder {
    $config = new ServiceConfigBuilder(ObjectInstantiator::class);

    $config->addPackages([
        ConfigManagerPackage::instance(),
        ConfigOptionsPackage::instance(),
        EntityGeneratorPackage::instance(),
        MockUpPackage::instance(),
        ServiceManagerPackage::instance(),
    ]);

    return $config;
});

service(ConfigManager::class)
    ->addDirectory(__DIR__ . '/tests/MockUps/config');
