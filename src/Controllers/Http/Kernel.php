<?php declare(strict_types = 1);

namespace BlendExchange\Controllers\Http;

use BlendExchange\Filesystem\Storage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

final class Kernel {
    private $routes;
    private $storage;

    public function __construct (array $routes,Storage $storage) {
        $this->routes = $routes;
        $this->storage = $storage;
    }

    public function checkForMaintenanceMode(Request $request) : bool {
        if ($this->storage->has('/site/downfile')) {
            $downfile = json_decode($this->storage->get('/site/downfile')->read());
            return !in_array(
                $request->getClientIp(),
                $downfile->allowed
            );
        }
        return false;
    }
    
    public function run(Request $request) {
        global $injector; //Woo! Service locator anti-pattern.
        /**
         * Perform Routing
         */

        $routes = new RouteCollection();
        $context = new RequestContext('/');
        $context->fromRequest($request);

        foreach ($this->routes as $name => $route) {
            $routes->add($name, $route);
        }

        $matcher = new UrlMatcher($routes, $context);

        $parameters = $matcher->matchRequest($request);

        /**
         * Check site is not in maintenance mode
         */

        if($this->checkForMaintenanceMode($request)) {
            $parameters = $routes->get('maintenance_mode')->getDefaults();
        }

        /**
         * Create Controller (And summon its dark powers)
         */

        $parts = explode(':', $parameters['_controller']);

        $controllerName = $parts[0];

        $controller = $injector->make($controllerName);

        //Obtain the arguments
        $arguments = [];
        foreach ($parameters as $key => $parameter) {
            if (substr($key, 0, 1) !== '_') {
                $arguments[':'.$key] = $parameter;
            }
        }

        //Cast the dark spell. Our response has been created!
        $methodName = $parts[2];
        $response = $injector->execute([$controller, $methodName], $arguments);

        //Don't forget to pack some snacks!
        $response->prepare($request);

        //Send or response off into the world. Good luck little fellow.
        $response->send();
    }
}


