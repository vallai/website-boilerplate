<?php

namespace Sources\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;

class ConnexionController {

	public function connexion(Application $app, Request $request) {
		// Si l'utilisateur est déjà connecté, on redirige vers l'accueil
        $utilisateur = $app['security']->getToken()->getUser();
        if ($utilisateur instanceof \Sources\Models\Utilisateur) {
            return $app->redirect($app['url_generator']->generate('accueil'));
        }
		
		// Affichage du formulaire de connexion
        $contenu = $app['twig']->render('connexion.html.twig', array(
            'target_path'   => $request->getSession()->get('_security.general.target_path'),
	        'error'         => $app['security.last_error']($request),
	        'last_username' => $app['session']->get('_security.last_username'),
    	));
        return new Response($contenu, 200);
	}
}