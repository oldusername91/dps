<?php

namespace App\Controller;

class LoginRegister extends SVController
{
    // /login
    public function GetLogin($request, $response, $args)
    {
        $this->logger->info("GET /login");
        $args['title']    = 'Login';

        return $this->renderer->render($response, 'login.phtml', $args);
    }

    // /login
    public function PostLogin($request, $response, $args)
    {
        // Sample log message
        $this->logger->info("POST /login");
        $args['title']    = 'Login';


        $POST    = $request->getParsedBody();
        $userrep = $this->em->getRepository('\App\Entity\User');
        $user    = $userrep->findOneBy(array('email' => $POST['email']));

        if ($user)
        {
            // Check the password
            $pwdhash   = $user->getPassword();
            $submitted = $POST['password'];

            if (password_verify($submitted, $pwdhash))
            {
                return $response->withRedirect($this->router->pathFor('root'), 303);
            }

        }
        else
        {
            $response->write("Wrong user name or password.");
            return $this->renderer->render($response, 'login.phtml', $args);
        }
    }

    // /register
    public function GetRegister($request, $response, $args)
    {
        // Sample log message
        $this->logger->info("GET /register");
        $args['title']    = 'Register';

        return $this->renderer->render($response, 'register.phtml', $args);
    }


    // /register
    public function PostRegister($request, $response, $args)
    {
        // Sample log message
        $this->logger->info("POST /register");
        $args['title']    = 'Register';


        $POST    = $request->getParsedBody();
        $userrep = $this->em->getRepository('\App\Entity\User');

        $newuser = new App\Entity\User();
        $newuser->setEmail($POST['email']);
        $newuser->setPassword(password_hash($POST['password'], PASSWORD_DEFAULT));
        $newuser->setJoined(new \DateTime());
        $newuser->setRole('member');

        $this->em->persist($newuser);
        $this->em->flush();


        return $this->renderer->render($response, 'register.phtml', $args);
    }

    // /members
    public function GetMembers($request, $response, $args)
    {

        $this->logger->info("GET /members");
        $args['title']    = 'Manage members';



        $userrep = $this->em->getRepository('\App\Entity\User');

        $users   = $userrep->findAll();
        $args['users'] = $users;


        return $this->renderer->render($response, 'members.phtml', $args);
    }


    // /members
    public function GetLogout($request, $response, $args)
    {

        $this->logger->info("GET /members");
        $args['title']    = 'Manage members';



        $userrep = $this->em->getRepository('\App\Entity\User');

        $users   = $userrep->findAll();
        $args['users'] = $users;


        return $this->renderer->render($response, 'members.phtml', $args);
    }
}
