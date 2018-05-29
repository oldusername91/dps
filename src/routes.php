<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes


$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");
    $args['SITE_DIR'] = '/home/wwwdocs/dps/';
    $args['SITE_URL'] = 'http://dps/';


    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});
