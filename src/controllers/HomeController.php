<?php

namespace Src\Controller;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

final class HomeController extends BaseController
{
    public function forbidden(Request $request, Response $response, $args)
    {
        $data = [
            'title' => "403 Forbidden",
            'profile' => $request->getAttribute("users")['user'],
            'granted' => $request->getAttribute("users")['permission'],
        ];

        $this->view->render($response, 'ample/users/403.html', $data);
        return $response;
    }

    public function getOptions(Request $request, Response $response, $args)
    {
        // get all inputs
        $input = $request->getParsedBody();

        $select = [
            'fldOptionId',
            'fldOption'
        ];

        $bind = [
            'for' => $input['for']
        ];

        // execute update sql command
        $for = $this->options->findAllBy($select, 'fldOptionFor = :for AND fldOptionDeleted = 0', $bind);

        // edit was successful
        $res = [
            'status' => 'success',
            'description' => $for
        ];

        // deliver the response to http
        return json_encode($res);
        exit;
    }

}
