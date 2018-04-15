<?php

// Routes
$app->group('/', function () {
    $this->get('', 'Src\Controller\HomeController:index')
        ->setName('home');
});
