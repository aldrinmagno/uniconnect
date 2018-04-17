<?php

namespace Src\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class HomeController extends BaseController
{
    public function index(Request $request, Response $response, $args)
    {
        $data = [
            'title' => "Home",
        ];

        $this->view->render($response, 'pages/home/index.html', $data);
        return $response;
    }

    public function regularSearch(Request $request, Response $response, $args)
    {
        $data = [
            'title' => "Universities around the world",
        ];

        $this->view->render($response, 'pages/home/search.html', $data);
        return $response;
    }

    public function aboutus(Request $request, Response $response, $args)
    {
        $data = [
            'title' => "About Us",
        ];

        $this->view->render($response, 'pages/home/aboutus.html', $data);
        return $response;
    }

    public function services(Request $request, Response $response, $args)
    {
        $data = [
            'title' => "Services",
        ];

        $this->view->render($response, 'pages/home/services.html', $data);
        return $response;
    }

    public function faq(Request $request, Response $response, $args)
    {
        $data = [
            'title' => "FAQ",
        ];

        $this->view->render($response, 'pages/home/faq.html', $data);
        return $response;
    }

}
