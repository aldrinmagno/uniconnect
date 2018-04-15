<?php

namespace Src\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class HomeController extends BaseController
{
    public function index(Request $request, Response $response, $args)
    {
        $data = [
            'title' => "403 Forbidden",
            'profile' => $request->getAttribute("users")['user'],
            'granted' => $request->getAttribute("users")['permission'],
        ];

        $this->view->render($response, 'ample/users/403.html', $data);
        return $response;
    }

}
