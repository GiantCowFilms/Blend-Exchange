<?php declare(strict_types = 1);
$config = require 'src/Configuration/main.php';

return  [
    'paths' => [
      'migrations' => 'migrations'
    ],
    'migration_base_class' => '\MyProject\Migration\Migration',
    'environments' => [
      'default_migration_table' => 'phinxlog',
      'default_database' => 'blend-exchange',
      'dev' => [
        'adapter' => $config['database']['driver'],
        'host' =>   $config['database']['host'],
        'name' =>   'blend-exchange',
        'user' =>   $config['database']['username'],
        'pass' => $config['database']['password'],
        'port' => 3306
      ],
      'production' => [
        'adapter' => $config['database']['driver'],
        'host' =>   $config['database']['host'],
        'name' =>   'blend-exchange',
        'user' =>   $config['database']['username'],
        'pass' => $config['database']['password'],
        'port' => 3306
      ]
    ]
  ];