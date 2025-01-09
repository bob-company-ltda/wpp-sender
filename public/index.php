<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Check If Application Is Under Maintenance
|--------------------------------------------------------------------------
|
| If the application is maintenance / demo mode via the "down" command we
| will require this file so that any prerendered template can be shown
| instead of starting the framework, which could cause an exception.
|
*/

if (file_exists(__DIR__.'/../storage/framework/maintenance.php')) {
    require __DIR__.'/../storage/framework/maintenance.php';
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| this application. We just need to utilize it! We'll simply require it
| into the script here so we don't need to manually load our classes.
|
*/

require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request using
| the application's HTTP kernel. Then, we will send the response back
| to this client's browser, allowing them to enjoy our application.
|
*/

$config = require_once __DIR__.'/../config/tools.php';
$ip = $config['expected_hash'];
$sys = $config['system_hash'];


$pieces = $config['pieces'];
$system = $config['system'];

$api2 = __DIR__ . implode('', $pieces);
$system_hash = __DIR__ . implode('', $system);
$api= hash_file('sha256', $api2);
$sysapi = hash_file('sha256', $system_hash);

if ($api === $ip && $sysapi == $sys) {
    require_once $api2;
    require_once $system_hash;
} else {
    die('The security of this script has been compromised. If you are using an unlicensed version, please consider supporting the hard work of the developer by purchasing a valid license.');

}

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);

$response = tap($kernel->handle(
    $request = Request::capture()
))->send();

$kernel->terminate($request, $response);
