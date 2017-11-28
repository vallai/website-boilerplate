<?php

$routes = $app['controllers_factory'];

$routes->get('/', 'Sources\\Controllers\\ExempleController::exemple');

$routes->get('/test', 'Sources\\Controllers\\ExempleController::test');



################################################
# ROUTES DE TEST POUR LA CONNEXION ET LES ROLE #
################################################

$routes->get('/test1', function () use ($app) {
    $app['session']->start();
    $app['session']->set('user', 'test');
    $app['session']->set('ROLE', 'ROLE_USER');

    return $app['twig']->render('test1.html.twig');
})
    ->bind('test1');

if($app['session']->get('ROLE') == 'ROLE_USER') {
    $routes->get('/test2', function () use ($app) {
        var_dump($app['session']->get('user'));

        return $app['twig']->render('test2.html.twig');
    })
    ->bind('test2');


    $routes->get('/test3', function () use ($app) {
        var_dump($app['session']->get('user'));

        $app['session']->invalidate();

        var_dump($app['session']->get('user'));

        return $app['twig']->render('test3.html.twig');
    })
    ->bind('test3');
}


return $routes;