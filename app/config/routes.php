<?php

$routes = $app['controllers_factory'];

$routes->get('/', 'Sources\\Controllers\\ExempleController::accueil')->bind('accueil');
$routes->get('/user', 'Sources\\Controllers\\ExempleController::user')->bind('user');
$routes->get('/admin', 'Sources\\Controllers\\ExempleController::admin')->bind('admin');
$routes->get('/connexion', 'Sources\\Controllers\\ConnexionController::connexion')->bind('connexion');

return $routes;