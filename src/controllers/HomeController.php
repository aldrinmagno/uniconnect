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
            'unis' => $this->uni->topUni(),
            'testimonies' => $this->users->testimonies()
        ];

        $this->view->render($response, 'pages/home/index.html', $data);
        return $response;
    }

    public function creator(Request $request, Response $response, $args)
    {
        $data = [
            'title' => "Creators",
            'breadcrums' => [[
                'name' => 'Home',
                'url' => $this->router->pathFor('home'),
                'status' => ''
            ], [
                'name' => 'About us',
                'url' => $this->router->pathFor('aboutus'),
                'status' => ''
            ], [
                'name' => 'Creators',
                'url' => $this->router->pathFor('aboutus'),
                'status' => 'active'
            ]],
        ];

        $this->view->render($response, 'pages/home/creator.html', $data);
        return $response;
    }

    public function regularSearch(Request $request, Response $response, $args)
    {
        $data = [
            'title' => "The right university for you",
            'breadcrums' => [[
                    'name' => 'Home',
                    'url' => $this->router->pathFor('home'),
                    'status' => ''
                ], [
                    'name' => 'Search University',
                    'url' => $this->router->pathFor('regularSearch'),
                    'status' => 'active'
                ]],
            'testimonies' => $this->users->testimonies()
        ];

        $this->view->render($response, 'pages/home/search.html', $data);
        return $response;
    }

    public function aboutus(Request $request, Response $response, $args)
    {
        $data = [
            'title' => "About us",
            'breadcrums' => [[
                    'name' => 'Home',
                    'url' => $this->router->pathFor('home'),
                    'status' => ''
                ], [
                    'name' => 'About us',
                    'url' => $this->router->pathFor('aboutus'),
                    'status' => 'active'
                ]],
            'creators' => [[
                    'image' => 'https://bulma.io/images/placeholders/1280x960.png',
                    'name' => 'Aldrin',
                    'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Ducimus repellat asperiores, id sequi doloribus exercitationem ab quam in modi tenetur dolores labore nam veniam minus ullam aliquam quisquam minima excepturi?'
                ], [
                    'image' => 'https://bulma.io/images/placeholders/1280x960.png',
                    'name' => 'Jasvir',
                    'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Ducimus repellat asperiores, id sequi doloribus exercitationem ab quam in modi tenetur dolores labore nam veniam minus ullam aliquam quisquam minima excepturi?'          
                ],  [
                    'image' => 'https://bulma.io/images/placeholders/1280x960.png',
                    'name' => 'Vilma',
                    'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Ducimus repellat asperiores, id sequi doloribus exercitationem ab quam in modi tenetur dolores labore nam veniam minus ullam aliquam quisquam minima excepturi?'          
                ],  [
                    'image' => 'https://bulma.io/images/placeholders/1280x960.png',
                    'name' => 'Irene',
                    'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Ducimus repellat asperiores, id sequi doloribus exercitationem ab quam in modi tenetur dolores labore nam veniam minus ullam aliquam quisquam minima excepturi?'          
                ], 
            ]
        ];

        $this->view->render($response, 'pages/home/aboutus.html', $data);
        return $response;
    }

    public function services(Request $request, Response $response, $args)
    {
        $data = [
            'title' => "Services",
            'breadcrums' => [[
                    'name' => 'Home',
                    'url' => $this->router->pathFor('home'),
                    'status' => ''
                ], [
                    'name' => 'Services',
                    'url' => $this->router->pathFor('services'),
                    'status' => 'active'
                ]],
            'testimonies' => $this->users->testimonies()
        ];

        $this->view->render($response, 'pages/home/services.html', $data);
        return $response;
    }

    public function faq(Request $request, Response $response, $args)
    {
        $data = [
            'title' => "FAQ",
            'breadcrums' => [[
                    'name' => 'Home',
                    'url' => $this->router->pathFor('home'),
                    'status' => ''
                ], [
                    'name' => 'FAQ',
                    'url' => $this->router->pathFor('faq'),
                    'status' => 'active'
                ]],
            'testimonies' => $this->users->testimonies()
        ];

        $this->view->render($response, 'pages/home/faq.html', $data);
        return $response;
    }

    public function login(Request $request, Response $response, $args)
    {
        $data = [
            'title' => "Login",
        ];

        $this->view->render($response, 'pages/home/login.html', $data);
        return $response;
    }

    public function listofLocations(Request $request, Response $response, $args)
    {
        $country = $this->country->findAll(['fldCountryId', 'fldCountryName']);

        $data = [];
        foreach($country as $row) {
            array_push($data, ['id' => $row['fldCountryId'], 'text' => $row['fldCountryName']]);
        }

        $result = [
            'results' => $data
        ];

        return json_encode($result);
    }

    public function listofDegree(Request $request, Response $response, $args)
    {
        $degree = $this->degree->findAll(['fldDegreeId', 'fldDegreeName']);

        $data = [];
        foreach($degree as $row) {
            array_push($data, ['id' => $row['fldDegreeId'], 'text' => $row['fldDegreeName']]);
        }

        $result = [
            'results' => $data
        ];

        return json_encode($result);
    }

}
