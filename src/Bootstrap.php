<?php declare(strict_types = 1);

namespace BlendExchange;

require __DIR__ . '/../vendor/autoload.php';

/**
 * Load Configuration
 */

$config = require 'Configuration/Main.php';

/**
* Register the error handler
*/

error_reporting(E_ALL);

$environment = $config['environment'];

$whoops = new \Whoops\Run;
if ($environment !== 'production') {
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
} else {
    $whoops->pushHandler(function($e){
        echo 'Todo: Friendly error page and send an email to the developer';
    });
}
$whoops->register();

/**
 * Inject Dependencies
 */

$injector = require 'Dependencies.php';

/**
 * Setup Database Connection
 */
//Lots of Laravel weirdness happens here. Therefore not doing by dependance injection
use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection($config['database']);

//Make this Capsule instance available globally.
$capsule->setAsGlobal();

// Setup the Eloquent ORM.
$capsule->bootEloquent();

/**
 * Create request object
 */

use Symfony\Component\HttpFoundation\Request;

$request = Request::createFromGlobals();

/**
 * Setup Twig (View Templates)
 */

$loader = new \Twig_Loader_Filesystem(__DIR__.'/Views');
$twig = new \Twig_Environment($loader, array(
    'cache' => __DIR__.'/Views/.cache',
));

$view = $twig;

/**
 * Perform Routing
 */

use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();
$context = new RequestContext('/');
$context->fromRequest($request);

$application_routes = include 'Routes.php';

foreach ($application_routes as $name => $route) {
    $routes->add($name,$route);
}

$matcher = new UrlMatcher($routes, $context);
$parameters = $matcher->match('/');

/**
 * Create Controller (And summon its dark powers)
 */

$parts = explode(':',$parameters['_controller']);
$controller = new $parts[0]($request,$view);

//Cast the dark spell. Our response has been created!
$response = $controller->{$parts[2]}();

//Don't forget to pack some snacks!
$response->prepare($request);

//Send or response off into the world. Good luck little fellow.
$response->send();