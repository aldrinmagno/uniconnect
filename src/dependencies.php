<?php

// DIC configuration
$container = $app->getContainer();

// -----------------------------------------------------------------------------
// Service providers
// -----------------------------------------------------------------------------

// Flash messages
$container['flash'] = function ($c) {
    return new \Slim\Flash\Messages;
};

// obfuscation
$container['obfuscation'] = function ($c) {
    return new \Jenssegers\Optimus\Optimus(512927377, 824040049, 1622990609);
};

// validation
$container['v'] = function($c) {
    return new \Sirius\Validation\Validator;
};

// validation helper
$container['vh'] = function($c) {
    return new \Sirius\Validation\Helper;
};

// curl
$container['curl'] = function($c) {
    return new \Curl\Curl;
};

$container['link'] = function($c) {
    $link = new \Mremi\UrlShortener\Model\Link;
    return $link;
};

// link shortner
$container['shortner'] = function($c) {
    return new \Mremi\UrlShortener\Provider\Google\GoogleProvider(
        'AIzaSyDjS2KfM7TmQo5CSOYMyXiDn1zQFcFbtTA',
        array('connect_timeout' => 1, 'timeout' => 1)
    );
};

// -----------------------------------------------------------------------------
// Service factories
// -----------------------------------------------------------------------------

// database
$container['db'] = function($c) {
    $settings = $c->get('settings');

    $pdo = new Aura\Sql\ExtendedPdo(
        'mysql:host='.$settings['db']['host'].';dbname='.$settings['db']['dbname'],
        $settings['db']['user'],
        $settings['db']['password']
    );
    return $pdo;
};

// Twig
$container['view'] = function ($c) {
    $settings = $c->get('settings');
    $view = new \Slim\Views\Twig($settings['view']['template_path'], $settings['view']['twig']);

    // Add extensions
    $view->addExtension(new Slim\Views\TwigExtension($c->get('router'), $c->get('request')->getUri()));
    $view->addExtension(new Twig_Extension_Debug());
    //$view->addExtension(new Src\View\CsrfExtension($c->get('csrf')));

    return $view;
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings');
    $logger = new \Monolog\Logger($settings['logger']['name']);
    $logger->pushProcessor(new \Monolog\Processor\UidProcessor());
    $logger->pushHandler(new \Monolog\Handler\StreamHandler($settings['logger']['path'], \Monolog\Logger::DEBUG));
    return $logger;
};

// session
$container['session'] = function ($c) {
    $session_factory = new \Aura\Session\SessionFactory;
    $session = $session_factory->newInstance($_COOKIE);
    return $session;
};

// csrf
$container['csrf'] = function ($c) {
    $guard = new \Slim\Csrf\Guard();
    $guard->setFailureCallable(function ($request, $response, $next) {
        $request = $request->withAttribute("csrf_status", false);
        return $next($request, $response);
    });

    return $guard;
};

// Swift Mailer
$container['mailer'] = function ($c) {
    $settings = $c->get('settings');
    // Create the Transport
    $transport = (new \Swift_SmtpTransport($settings['SwiftMailer']['server'], 465, 'ssl'))
      ->setUsername($settings['SwiftMailer']['username'])
      ->setPassword($settings['SwiftMailer']['password'])
    ;

    // Create the Mailer using your created Transport
    $mailer = new \Swift_Mailer($transport);

    return $mailer;
};

// optimus
$container['optimus'] = function ($c) {
    return new \Jenssegers\Optimus\Optimus(329302871, 1182849127, 1662725458);
};

// -----------------------------------------------------------------------------
// Controller factories
// -----------------------------------------------------------------------------
$container['Src\Controller\HomeController'] = function ($c) {
    return new Src\Controller\HomeController($c);
};

// -----------------------------------------------------------------------------
// Model factories
// -----------------------------------------------------------------------------
$container['users'] = function ($c) {
    return new Src\Model\UsersModel($c);
};