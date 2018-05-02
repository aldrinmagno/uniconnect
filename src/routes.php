<?php

$app->group('/profile', function () {
    $this->get('', 'Src\Controller\ProfileController:myProfile')
        ->setName('myprofile');
    $this->get('/check-list', 'Src\Controller\ProfileController:checkList')
        ->setName('checklist');
    $this->get('/my-university', 'Src\Controller\ProfileController:selectedUni')
        ->setName('myuniversity');
    $this->get('/getting-ready-for-university-life', 'Src\Controller\ProfileController:activities')
        ->setName('gettingready');
    $this->get('/connect-with-other-students', 'Src\Controller\ProfileController:connect')
        ->setName('connect');
});

// home
$app->group('/', function () {
    $this->get('', 'Src\Controller\HomeController:index')
        ->setName('home');
    $this->get('search-universities', 'Src\Controller\HomeController:regularSearch')
        ->setName('regularSearch');
    $this->get('about-us', 'Src\Controller\HomeController:aboutus')
        ->setName('aboutus');
    $this->get('services', 'Src\Controller\HomeController:services')
        ->setName('services');
    $this->get('faq', 'Src\Controller\HomeController:faq')
        ->setName('faq');
    
    $this->get('list-of-locations', 'Src\Controller\HomeController:listofLocations')
        ->setName('listOfLocation');
    $this->get('list-of-degree', 'Src\Controller\HomeController:listofDegree')
        ->setName('listOfDegree');

    $this->get('login', 'Src\Controller\HomeController:login')
        ->setName('login');
});

$app->group('/creators', function () {
    $this->get('/{creator}', 'Src\Controller\HomeController:creator')
        ->setName('creator');
});

$app->group('/', function () {
    $this->get('{country}', 'Src\Controller\SearchController:listofUniversities')
    ->setName('search');
    $this->get('{country}/{uni}', 'Src\Controller\SearchController:university')
    ->setName('university');
});