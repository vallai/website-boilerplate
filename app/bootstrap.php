<?php

use Csanquer\Silex\PdoServiceProvider\Provider\PDOServiceProvider;
use Silex\Application;

$app = new Application();

// Activation du mode debug
$app['debug'] = true;

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

// Connexion pour bdd
$app->register(
    new PDOServiceProvider('pdo'), array(
    	'pdo.server' => parse_ini_file('config/db.ini')
	)
);

// $pdo = $app['pdo'];

return $app;