<?php

namespace App\Controller;

// Parent class of all controllers. Constructor loads all our dependencies we
// want accessible in a controller.
class SVController
{
    public $renderer;
    public $em;
    public $logger;

    public function __construct($router, $renderer, $em, $logger){
        $this->router   = $router;
        $this->renderer = $renderer;
        $this->em       = $em;
        $this->logger   = $logger;
    }
}
