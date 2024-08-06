<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
\App\Models\Helpers\MyHelper::get_pr('index.php опапли сюда0');
(require_once __DIR__.'/../bootstrap/app.php')
    ->handleRequest(Request::capture());

require_once __DIR__ . '/../config/init.php';
require_once __DIR__ . '/../config/params.php';
\App\Models\Helpers\MyHelper::get_pr('index.php опапли сюда1');
new \App\Sblog\Core\BlogApp();
\App\Models\Helpers\MyHelper::get_pr('index.php опапли сюда2');
