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

    // models
    protected $users;
    protected $accomodation;
    protected $country;
    protected $degree;
    protected $image;
    protected $location;
    protected $requirement;
    protected $uni;
    protected $useruni;
  
    // routes
    protected $router;

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
        
        // models
        $this->users = $c->get('users');
        $this->accomodation = $c->get('accomodation');
        $this->country = $c->get('country');
        $this->degree = $c->get('degree');
        $this->image = $c->get('image');
        $this->location = $c->get('location');
        $this->requirement = $c->get('requirement');
        $this->uni = $c->get('uni');
        $this->useruni = $c->get('useruni');

        // routes
        $this->router = $c->get('router');
     
    }
}