<?php declare(strict_types = 1);

namespace BlendExchange\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use BlendExchange\Handlers\AuthHandler;
use BlendExchange\Authorization;
class BaseController
{
    protected $request;
    protected $view;
    protected $response;
    protected $user;

    public function __construct (Authorization\User $user,\Twig_Environment $view)
    {
        $this->view = $view;
        $this->user = $user;
    }

    protected function fail ($errors,$code = 500) {
        $this->response->setContent(json_encode($errors));
        $this->response->headers->set('Content-Type', 'application/json');
        $this->response->setStatusCode($code);
    }

    protected function failFromException($error) {
        return $this->fail($error->message,$error->code);
    }

    protected function permanentRedirect ($url) {
        $this->response->headers->set('Location', $url);
        $this->response->setStatusCode(301);
    }

    protected function redirect ($url,$code = 302) {
        $this->response->headers->set('Location', $url);
        $this->response->setStatusCode($code);
    }

    protected function json($data) {
        $this->response->setContent(json_encode($data));
        $this->response->headers->set('Content-Type', 'application/json');
    }

    protected function protect($isRole = []) {

    }

    protected function guest () {
        if ($this->user !== null) {
            throw new \Exception('Logged in user attempting guest route.');
        }
    }
}