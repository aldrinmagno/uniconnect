<?php

// Routes
$app->group('/', function () {
    $this->get('', 'Src\Controller\UserController:loginView')
        ->setName('loginPageUser');
});
