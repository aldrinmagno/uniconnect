<?php

namespace Src\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class ProfileController extends BaseController
{
    public function myProfile(Request $request, Response $response, $args)
    {
        $data = [
            'title' => "Dashboard",
        ];

        $this->view->render($response, 'pages/profile/myprofile.html', $data);
        return $response;
    }

    public function checkList(Request $request, Response $response, $args)
    {
        $data = [
            'title' => "Get started with your academic dreams",
        ];

        $this->view->render($response, 'pages/profile/checklist.html', $data);
        return $response;
    }

    public function connect(Request $request, Response $response, $args)
    {
        $data = [
            'title' => "Connect with other students",      
            'testimonies' => $this->users->students()
        ];

        $this->view->render($response, 'pages/profile/connect.html', $data);
        return $response;
    }

    public function activities(Request $request, Response $response, $args)
    {
        $data = [
            'title' => "Activities",
        ];

        $this->view->render($response, 'pages/profile/activities.html', $data);
        return $response;
    }

    public function selectedUni(Request $request, Response $response, $args)
    {
        $data = [
            'title' => "Your Selected University",
        ];

        $this->view->render($response, 'pages/profile/selected-uni.html', $data);
        return $response;
    }
}
