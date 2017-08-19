<?php

$app = new Silex\Application();

// Enable the debug mode
$app['debug'] = true;

$app->mount('/', include BASE_DIRECTORY_PROJECT . '/app/config/routes.php');

$app->register(new Silex\Provider\TwigServiceProvider(), [
	'twig.path' => BASE_DIRECTORY_PROJECT . '/views',
	'twig.options' => [ // Options de TWIG
        'charset' => 'utf-8', // Encodage utilisé par les templates
        'strict_variables' => true // TWIG n'acceptera pas les variables et méthodes inexistantes
    ]
]);

return $app;