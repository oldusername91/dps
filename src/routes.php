<?php

use \App;
use Slim\Http\Request;
use Slim\Http\Response;



// Routes
$container = $app->getContainer();

$app->get('/',
function (Request $request, Response $response, array $args)
{
    $args['title']    = 'Home';
    return $this->renderer->render($response, 'index.phtml', $args);
});


$app->get('/login', 'App\Controller\LoginRegister:GetLogin');
$app->post('/login', 'App\Controller\LoginRegister:PostLogin');


$app->get('/register', 'App\Controller\LoginRegister:GetRegister');
$app->post('/register', 'App\Controller\LoginRegister:PostRegister');
