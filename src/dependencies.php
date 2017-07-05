<?php
$container = $app->getContainer();
$container['view'] = function($c){
	$view = new \Slim\Views\Twig(__DIR__.'/template', [
		'cache' => __DIR__.'/template_cache'
	]);
	$basePath = rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
	$view->addExtension(new Slim\Views\TwigExtension($c['router'], $basePath));

	return $view;
};
$container['db_sqlite'] = function($c){
	$db = new PDO("sqlite:../db/renthouse.sqlite");
	return $db;
};
// $container[Controller\HelloController::class] = function ($c) {
//     return new Controller\HelloController();
// };