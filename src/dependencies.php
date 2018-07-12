<?php


use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\Setup;
use Slim\Container;
use GuzzleHttp\Client;
use App\Resources\NSWFuelAPI;
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

// Doctrine
$container['em'] = function (Container $container): EntityManager {
    $config = Setup::createAnnotationMetadataConfiguration(
        $container['settings']['doctrine']['metadata_dirs'],
        $container['settings']['doctrine']['dev_mode']
    );

    $config->setMetadataDriverImpl(
        new AnnotationDriver(
            new AnnotationReader,
            $container['settings']['doctrine']['metadata_dirs']
        )
    );

    $config->setMetadataCacheImpl(
        new FilesystemCache(
            $container['settings']['doctrine']['cache_dir']
        )
    );

    return EntityManager::create(
        $container['settings']['doctrine']['connection'],
        $config
    );
};

$container['session'] = function ($c) {
  return new \SlimSession\Helper;
};


$container['GuzzleForNSWAPI'] = function ($c) {
    $client = new Client([
      // Base URI is used with relative requests
      'base_uri' => 'https://api.onegov.nsw.gov.au'
  ]);
  return $client;
};


$container['FuelAPI'] = function ($c) {
    return new NSWFuelAPI($c->GuzzleForNSWAPI, $c->logger);
};

// For each controller.
$container['App\Controller\LoginRegister'] = function ($c) {
    return new App\Controller\LoginRegister($c->get('router'), $c->get('renderer'), $c->get('em'), $c->get('logger'));
};
