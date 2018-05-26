<?php declare(strict_types = 1);

namespace BlendExchange;

require __DIR__ . '/../vendor/autoload.php';

/**
 * Load Configuration
 */

$config = require 'Configuration/Main.php';

/**
 * Add Logging
 */



/**
* Register the error handler
*/

error_reporting(E_ALL);

$environment = $config['environment'];

$whoops = new \Whoops\Run;
if ($environment !== 'production') {
    $whoops->pushHandler(new \Whoops\Handler\PlainTextHandler);
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
} else {
    $whoops->pushHandler(function () {
        return 'Something broke. Sorry.';
    });
}
$whoops->register();

/**
 * Inject Dependencies
 */

$injector = require 'Dependencies.php';

/**
 * Error handlers with dependencies
 */

if ($environment === 'production') {
    $whoops->pushHandler(
        $injector->make(\BlendExchange\Exception\Handler\EmailHandler::class,[
            ':to' => $config['email']['dev_email']
        ])
    );
}

$logger = $injector->make('Monolog\Logger');
$whoops->pushHandler(
    $injector->make(\BlendExchange\Exception\Handler\LogHandler::class));


/**
 * Setup Database Connection
 */
$database = require 'Database.php';

/**
 * Handle Http Request
 */

function handle_http_request() {
    global $injector;
    $request = $injector->make('HttpFoundation\Request');
    $routes = require 'Routes.php';
    $httpKernel = $injector->make(Controllers\Http\Kernel::class,[':routes' => $routes ]);
    $httpKernel->run($request);
}

/**
 * Handle Command
 */
use Symfony\Component\Console\Application;
function handle_console_command() {
    global $injector;

    $application = new Application();

    $commands = require 'Commands.php';

    foreach($commands as $command) {
        $application->add($injector->make($command));
    }

    $application->run();
}
