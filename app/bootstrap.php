<?php

use Csanquer\Silex\PdoServiceProvider\Provider\PDOServiceProvider;
use Silex\Application;
use Silex\Provider\UrlGeneratorServiceProvider;

$app = new Application();

// Activation du mode debug
$app['debug'] = true;

// Sessions
$app->register(new Silex\Provider\SessionServiceProvider());

// Routes
$app->mount('/', include BASE_DIRECTORY_PROJECT . '/app/config/routes.php');

// Configuration de Twig
$app->register(new Silex\Provider\TwigServiceProvider(), [
	'twig.path' => BASE_DIRECTORY_PROJECT . '/views',
	'twig.options' => [ // Options de TWIG
        'charset' => 'utf-8', // Encodage utilisé par les templates
        'strict_variables' => true // TWIG n'acceptera pas les variables et méthodes inexistantes
    ]
]);

// Function to generate URLs with TWIG
$app->register(new UrlGeneratorServiceProvider());
$app['twig']->addFunction(new \Twig_SimpleFunction('path', function($url) use ($app) {
    return $app['url_generator']->generate($url);
}));

// Connexion pour bdd
$app->register(
    new PDOServiceProvider('pdo'), array(
    	// Récupération des informations de connexion à la bdd
    	'pdo.server' => parse_ini_file('config/db.ini')
	)
);

return $app;