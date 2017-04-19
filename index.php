<?php

use App\Controllers\HomeController;

require 'vendor/autoload.php';

$app = new Slim\App([
	'settings' => [
		'displayErrorDetails' => true,
	]
]);

// Get container
$container = $app->getContainer();

// Register component on container
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(__DIR__ . '/resources/views', [
        'cache' => false,
    ]);
    
    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

    return $view;
};

$container['db'] = function() {
    return new PDO('pgsql:host=localhost;port=5432;dbname=forum', 'user=postgres', 'password=password');
};

$app->get('/', HomeController::class . ':index');

$app->get('/home', function($request, $response) {
	return $this->view->render($response, 'home.twig');
});

$app->run();