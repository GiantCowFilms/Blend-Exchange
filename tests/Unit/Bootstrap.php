<?php declare(strict_types = 1);

namespace BlendExchange\Test;

require __DIR__ . '/../../vendor/autoload.php';

/**
 * Load Configuration
 */

$config = require './src/Configuration/Main.php';

/**
 * Inject Dependencies
 */

$injector = require './src/Dependencies.php';

require 'Dependencies.php';

/**
 * Setup Database Connection
 */
$database = require './src/Database.php';