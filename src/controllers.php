<?php

$app->get('/',
        function () use ($app) {
            return $app['twig']->render('home.twig');
        });

$app->get('/votaciones/asistencia',
        function () use ($app) {
            return $app['twig']->render(
                            'Session/assistence.twig',
                            array(
                        'title' => 'Asistencias',
                        'assistency' => $app['kpacha_dtpt.monitor']->getAssistency(true)
                            )
            );
        });

$app->get('/votaciones/ausencia',
        function () use ($app) {
            return $app['twig']->render(
                            'Session/assistence.twig',
                            array(
                        'title' => 'Ausencias',
                        'assistency' => $app['kpacha_dtpt.monitor']->getAssistency(false)
                            )
            );
        });

$app->get('/sesiones/resumen',
        function () use ($app) {
            return $app['twig']->render(
                            'Session/aggregate.twig',
                            array(
                        'data' => $app['kpacha_dtpt.monitor']->getSessions(false)
                            )
            );
        });

$app->get('/sesiones',
        function () use ($app) {
            return $app['twig']->render(
                            'Session/list.twig',
                            array(
                        'data' => $app['kpacha_dtpt.monitor']->getSessions(true)
                            )
            );
        });