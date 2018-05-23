<?php declare(strict_types = 1);

namespace BlendExchange\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class BaseController
{
    protected $request;
    protected $view;
    protected $response;

    public function __construct (Request $req, Response $res,\Twig_Environment $view)
    {
        $this->request = $req;
        $this->response = $res;
        $this->view = $view;
    }
}