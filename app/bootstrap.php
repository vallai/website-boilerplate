<?php

use Csanquer\Silex\PdoServiceProvider\Provider\PDOServiceProvider;
use Silex\Application;
use Silex\Provider\UrlGeneratorServiceProvider;
use Sources\Helpers\UserProvider;

$app = new Application();

// Activation du mode debug
$app['debug'] = true;

// Connexion pour bdd
$app->register(
    new PDOServiceProvider('pdo'), array(
    	// Récupération des informations de connexion à la bdd
    	'pdo.server' => parse_ini_file('config/db.ini')
	)
);
$pdo = $app['pdo']; // J'ai mis ça pour utiliser $pdo en tant que globale dans la classe Utilisateur, à toi de voir si tu trouves mieux :)

// Sessions
$app->register(new Silex\Provider\SessionServiceProvider());

// Function to generate URLs with TWIG
$app->register(new UrlGeneratorServiceProvider());

// Configuration de Twig
$app->register(new Silex\Provider\TwigServiceProvider(), [
	'twig.path' => BASE_DIRECTORY_PROJECT . '/views',
	'twig.options' => [ // Options de TWIG
        'charset' => 'utf-8', // Encodage utilisé par les templates
        'strict_variables' => true // TWIG n'acceptera pas les variables et méthodes inexistantes
    ]
]);

// Mise en place du système de connexion
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'default' => array(
            'pattern' => '^.*$', // Concerne tout le projet
            'anonymous' => true, // Indispensable car la zone de login se trouve dans la zone sécurisée (tout le front-office)
            'form' => array(
				'login_path' => '/connexion', // /connexion sera appelé pour connecter l'utilisateur s'il tente d'acceder à une page protégée.
				'check_path' => '/login_check'), // /login_check est géré par Silex, pas besoin de le definir dans les routes
            'logout' => array('logout_path' => '/deconnexion'), // /deconnexion est géré par Silex, pas besoin de le definir dans les routes
            'users' => $app->share(function() {
				return new UserProvider(); // On remplace le UserProvider de Symfony par le notre, pour utiliser notre propre classe Utilisateur
            })
        ),
    )
));
			
// Restriction de certaines routes
$app['security.access_rules'] = array(
	array('^/user$', 'ROLE_USER'), // /user est accessible uniquement aux personnes avec le role ROLE_USER
	array('^/admin$', 'ROLE_ADMIN'), // /admin est accessible uniquement aux personnes avec le role ROLE_ADMIN
);
			
// Service de hiérarchie des rôles
$app['security.role_hierarchy'] = array(
    'ROLE_ADMIN' => array('ROLE_USER', 'ROLE_ALLOWED_TO_SWITCH'), // L'admin possède également les droits utilisateurs, et est autorisé à switcher de l'un à l'autre
);

// Routes
$app->mount('/', include BASE_DIRECTORY_PROJECT . '/app/config/routes.php');

return $app;