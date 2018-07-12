<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);

// Register with container
// $container = $app->getContainer();
// $container['csrf'] = function ($c) {
//     return new \Slim\Csrf\Guard;
// };


$container = $app->getContainer();

// $app->add(new \Slim\Middleware\Session([
//   'name'          => 'session',
//   'autorefresh'   => true,
//   'lifetime'      => '10 seconds'
// ]));

// $app->add(new \Tuupola\Middleware\HttpBasicAuthentication([
//     "authenticator" => new AppAuthenticator($container),
//     "realm"  => "Protected",
//     "secure" => false
// ]));
