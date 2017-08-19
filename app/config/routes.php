<?php

$routes = $app['controllers_factory'];

$routes->get('/', 'Sources\\Controllers\\ExempleController::exemple');

return $routes;