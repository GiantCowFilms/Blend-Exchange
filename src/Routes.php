<?php declare(strict_types = 1);

use Symfony\Component\Routing\Route;

return [
    'main_page' => new Route('/',[
        '_controller' => 'BlendExchange\Controllers\SiteController::mainPage'
    ])
];