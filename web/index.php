<?php
if (!defined('APPLICATION_ENV')) {
    define('APPLICATION_ENV', (getenv('APPLICATION_ENV'))?:'production');
}
require_once __DIR__.'/../src/app.php';

$app->run();
