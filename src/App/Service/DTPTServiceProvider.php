<?php

namespace App\Service;

use Silex\Application;
use Silex\ServiceProviderInterface;

class DTPTServiceProvider implements ServiceProviderInterface
{

    public function register(Application $app)
    {
        $this->registerConnection($app);
        $this->registerTrack($app);
    }

    public function registerConnection(Application $app)
    {
        $app['kpacha_dtpt.mongodb.connection'] = $app->share(function ($app) {
                    return new \Mongo($app['parameters']['mongodb']['url']);
                });
    }

    public function registerTrack(Application $app)
    {

        if (!isset($app['kpacha_dtpt.monitor.class'])) {
            $app['kpacha_dtpt.monitor.class'] = 'App\Service\MonitorService';
        }

        $app['kpacha_dtpt.monitor'] = $app->share(function ($app) {
                    return new $app['kpacha_dtpt.monitor.class'](
                                    $app['kpacha_dtpt.sesiones.collection']
                    );
                });

        $app['kpacha_dtpt.sesiones.collection'] = $app->share(function ($app) {
                    return $app['kpacha_dtpt.mongodb.connection']->selectCollection(
                                    $app['parameters']['mongodb']['db'], 'sesiones'
                    );
                });
    }

    public function boot(Application $app)
    {
        
    }

}
