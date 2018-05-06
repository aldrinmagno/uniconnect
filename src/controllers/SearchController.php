<?php

namespace Src\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class SearchController extends BaseController
{
    public function listofUniversities(Request $request, Response $response, $args)
    {
        $country = $args['country'];
  
        $country = $this->country->findBy(['fldCountryName', 'fldCountrySlug', 'fldCountryId'], 'fldCountrySlug = :slug', ['slug' => $country]);

        $data = [
            'title' => "Best Universities Australia",
            'breadcrums' => [[
                'name' => 'Home',
                'url' => $this->router->pathFor('home'),
                'status' => ''
            ], [
                'name' => 'Search University',
                'url' => $this->router->pathFor('regularSearch'),
                'status' => ''
            ], [
                'name' => $country['fldCountryName'],
                'url' => $this->router->pathFor('search', ['country' => $country['fldCountrySlug']]),
                'status' => 'active'
            ]],
            'universities' => $this->uni->universities($country['fldCountryId']),
        ];

        $this->view->render($response, 'pages/directories/list-of-uni.html', $data);
        return $response;
    }

    public function university(Request $request, Response $response, $args)
    {
        $uni = $args['uni'];
        $country = $args['country'];
  
        $uni = $this->uni->findBy(['*'], 'fldUniSlug = :slug', ['slug' => $uni]);
        $country = $this->country->findBy(['*'], 'fldCountrySlug = :slug', ['slug' => $country]);
        $degree = $this->degree->findAllBy(['*'], 'tblUni_fldUniId = :id', ['id' => $uni['fldUniId']]);
    
        $data = [
            'title' => $uni['fldUniName'],
            'testimonies' => $this->users->testimonies(),
            'breadcrums' => [[
                'name' => 'Home',
                'url' => $this->router->pathFor('home'),
                'status' => ''
            ], [
                'name' => 'Search University',
                'url' => $this->router->pathFor('regularSearch'),
                'status' => ''
            ], [
                'name' => $country['fldCountryName'],
                'url' => $this->router->pathFor('search', ['country' => $country['fldCountrySlug']]),
                'status' => ''
            ], [
                'name' => $uni['fldUniName'],
                'url' => '',
                'status' => 'active'
            ]],
            'uni' => $uni,
            'country' => $country,
            'degrees' => $degree
        ];

        $this->view->render($response, 'pages/directories/university.html', $data);
        return $response;
    }

    public function register(Request $request, Response $response, $args)
    {
        $segment = $this->session->getSegment('Aura\Session\SessionFactory');
        $user = $segment->get('userData');

        if($user) {
            return $response->withStatus(302)->withHeader('Location', $this->router->pathFor('myprofile'));
        }

        $uni = $args['uni'];
        $uni = $this->uni->findBy(['*'], 'fldUniSlug = :slug', ['slug' => $uni]);
        $country = $this->country->findBy(['*'], 'fldCountryId = :slug', ['slug' => $uni['tblCountry_fldCountryId']]);

        $data = [
            'title' => 'Apply to ' . $uni['fldUniName'],
            'uni' => $uni,
            'country' => $country
        ];

        $this->view->render($response, 'pages/directories/register.html', $data);
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
        $data = [
            'results' => [[
                'id' => 1,
                'text' => 'Australia'
            ], [
                'id' => 2,
                'text' => 'New Zealand'
            ], [
                'id' => 3,
                'text' => 'Canada'
            ], [
                'id' => 4,
                'text' => 'United State of America'
            ], [
                'id' => 5,
                'text' => 'United Kingdom'
            ]]
        ];

        return json_encode($data);
    }

}
