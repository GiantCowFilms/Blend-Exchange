<?php declare(strict_types = 1);

$injector = new \Auryn\Injector;

/**
 * Setup Request
 */

$injector->alias('HttpFoundation\Request', 'Symfony\Component\HttpFoundation\Request');
$injector->share('Symfony\Component\HttpFoundation\Request');
$injector->define('HttpFoundation\Request', [
    ':query' => $_GET,
    ':request' => $_POST,
    ':attributes' => [],
    ':cookies' => $_COOKIE,
    ':files' => $_FILES,
    ':server' => $_SERVER,
]);


/**
 * Setup Response
 */

$injector->alias('HttpFoundation\Response', 'Symfony\Component\HttpFoundation\Response');
$injector->share('Symfony\Component\HttpFoundation\Response');

/**
 * Setup Twig (View Templates)
 */

$injector->define('Twig_Loader_Filesystem',[
    ':paths' => __DIR__.'/Views',
]);

$injector->define('Twig_Environment',[
    ':options' => [
        'cache' => __DIR__.'/Views/.cache'
    ]
]);
 

return $injector;