<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app->get('/admin/drop',
        function () use ($app) {
            return $app->json($app['kpacha_dtpt.sesiones.collection']->drop());
        });

$app->get('/admin/list',
        function () use ($app) {
            return $app->json(iterator_to_array($app['kpacha_dtpt.sesiones.collection']->find()->limit(10)));
        });

$app->get('/admin/find',
        function () use ($app) {
            return $app->json(iterator_to_array($app['kpacha_dtpt.monitor']->find()->limit(10)));
        });

$app->post('/admin/sesiones/uptade',
        function (Request $request) use ($app) {
            $serializedSession = $request->get('sesion');
            $session = json_decode($serializedSession);
            $app['kpacha_dtpt.monitor']->insert($session);

            return new Response('Ok', 201);
        });