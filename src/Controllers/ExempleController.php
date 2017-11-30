<?php

namespace Sources\Controllers;

use \Silex\Application;

class ExempleController {

	public function accueil(Application $app) {
		return $app['twig']->render('accueil.html.twig');
	}
	
	public function user(Application $app) {
		echo "Page accessible uniquement avec le ROLE_USER";exit;
	}
	
	public function admin(Application $app) {
		echo "Page accessible uniquement avec le ROLE_ADMIN";exit;
	}
	
}