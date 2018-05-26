<?php  declare(strict_types = 1);
//Lots of Laravel weirdness happens here. Therefore not doing by dependency injection
use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection($config['database']);

//Make this Capsule instance available globally.
$capsule->setAsGlobal();

// Setup the Eloquent ORM.
$capsule->bootEloquent();

return $capsule;
