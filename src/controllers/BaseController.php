<?php

namespace Src\Controller;

use Slim\Container;

/**
 * @author Aldrin Magno <aldrinmagno@gmail.com>
 */
class BaseController
{
    // packages
    protected $view;
    protected $v;
    protected $vh;
    protected $csrf;
    protected $session;
    protected $mailer;
    protected $optimus;
   
    public function __construct(Container $c)
    {
        // packages
        $this->view = $c->get('view');
        $this->v = $c->get('v');
        $this->vh = $c->get('vh');
        $this->session = $c->get('session');
        $this->csrf = $c->get('csrf');
        $this->mailer = $c->get('mailer');
        $this->optimus = $c->get('optimus');
     
    }
}