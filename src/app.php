<?php
$parameters = require_once __DIR__.'/../config/parameters_'. APPLICATION_ENV .'.php';
require_once __DIR__.'/../vendor/autoload.php';

use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use App\Service\DTPTServiceProvider;

$app = new Application();
if (APPLICATION_ENV == 'development') {
    $app['debug'] = true;
}

$app['parameters'] = $parameters;

$app->register(new TwigServiceProvider, array(
    'twig.path' => __DIR__.'/../views',
));

$app->register(new DTPTServiceProvider, $parameters);

require_once 'controllers.php';
require_once 'ops_controllers.php';

return $app;