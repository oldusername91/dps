<?php

use \App;
use Slim\Http\Request;
use Slim\Http\Response;


// Routes
$container = $app->getContainer();

$app->get('/',
function (Request $request, Response $response, array $args)
{
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");
    $args['SITE_DIR'] = '/home/wwwdocs/dps/';
    $args['SITE_URL'] = 'http://dps/';
    $args['title']    = 'Login';


    foreach($this->em->getMetadataFactory()->getAllMetadata() as $classMeta) {
        $response->write("Printing entities: " . $classMeta->getName());
    }
    $this->em->getRepository('App\Entity\User');

    // $this->em->getRepository('\App\Entity\User');
    return $this->renderer->render($response, 'login.phtml', $args);
    // Render index view
});


$app->post('/login',
function (Request $request, Response $response, array $args)
{
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' login");
    $args['SITE_DIR'] = '/home/wwwdocs/dps/';
    $args['SITE_URL'] = 'http://dps/';
    $args['title']    = 'Login';




    foreach($this->em->getMetadataFactory()->getAllMetadata() as $classMeta) {
        $response->write("Printing entities: " . $classMeta->getName());
    }

    $POST    = $request->getParsedBody();
    $userrep = $this->em->getRepository('App\Entity\User');
    $user    = $userrep->findOneBy(array('email' => $POST['email']));

    if ($user)
    {
        // Check the password
        $pwdhash   = $user->getPassword();
        $submitted = $POST['password'];

        if (password_verify($submitted, $pwdhash))
        {
            $response->write("Congrats you logged on.");
        }

    }


    return $this->renderer->render($response, 'login.phtml', $args);
    // Render index view
});


$app->get('/register',
function (Request $request, Response $response, array $args)
{
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");
    $args['SITE_DIR'] = '/home/wwwdocs/dps/';
    $args['SITE_URL'] = 'http://dps/';
    $args['title']    = 'Login';

    foreach($this->em->getMetadataFactory()->getAllMetadata() as $classMeta) {
        $response->write("Printing entities: " . $classMeta->getName());
    }

    $POST = $request->getParsedBody();
    $userrep = $this->em->getRepository('App\Entity\User');
    $newuser = new App\Entity\User();
    $newuser->setEmail($POST['email']);
    $newuser->setPassword(password_hash($POST['password'], PASSWORD_DEFAULT));
    $newuser->setJoined(new \DateTime());

    $this->em->persist($newuser);
    $this->em->flush();


    return $this->renderer->render($response, 'register.phtml', $args);
    // Render index view
});
