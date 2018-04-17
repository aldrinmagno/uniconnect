<?php

// Routes
$app->group('/', function () {
    $this->get('', 'Src\Controller\HomeController:index')
        ->setName('home');
    $this->get('search', 'Src\Controller\HomeController:regularSearch')
        ->setName('regularSearch');
    $this->get('about-us', 'Src\Controller\HomeController:aboutus')
        ->setName('aboutus');
    $this->get('services', 'Src\Controller\HomeController:services')
        ->setName('services');
    $this->get('faq', 'Src\Controller\HomeController:faq')
        ->setName('faq');
    $this->get('{country}', 'Src\Controller\HomeController:search')
        ->setName('search');
});
