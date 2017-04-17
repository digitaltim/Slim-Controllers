<?php

use App\Controllers\HomeController;

require 'vendor/autoload.php';

$app = new Slim\App([
	'settings' => [
		'displayErrorDetails' => true,
	]
]);

$app->get('/', HomeController::class . ':index');

$app->get('/home', function() {
	return 'Home';
});

$app->run();