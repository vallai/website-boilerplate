<?php

namespace Sources\Controllers;

use \Silex\Application;

class ExempleController {

	public function exemple(Application $app) {
		return $app['twig']->render('exemple.html.twig');
	}

	public function test(Application $app) {
	    return $app['twig']->render('test.html.twig');
    }

}