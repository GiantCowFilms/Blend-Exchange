<?php declare(strict_types = 1);

namespace BlendExchange\Controllers;

use Symfony\Component\HttpFoundation\Response;

class SiteController extends BaseController
{
    public function mainPage() {
        return new Response($this->view->render('Pages/main_page.twig'));
    }

    public function privacyPolicy() {

    }

    public function termsOfService () {

    }

    public function about () {

    }

    public function contribute () {

    }
}