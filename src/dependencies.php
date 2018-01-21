<?php
$container = $app->getContainer();
$container['view'] = function($c){
	$view = new \Slim\Views\Twig(__DIR__.'/views', [
		//'cache' => __DIR__.'/template_cache',
		'cache' => false,
		'debug' => true
	]);
	$basePath = rtrim(str_ireplace('index.php', '', $c['request']->getUri()->getBasePath()), '/');
	$view->addExtension(new Slim\Views\TwigExtension($c['router'], $basePath));
	$view->addExtension(new Twig_Extension_Debug());

	$view->getEnvironment()->addFilter(new Twig_SimpleFilter('date_day', function($string){
		return date('d', strtotime($string));
	}));
	$view->getEnvironment()->addFilter(new Twig_SimpleFilter('price_format', function($string){
		return 'Rp. ' . number_format($string, 0 , '' , '.' ) . ',-';
	}));
	$view->getEnvironment()->addFilter(new Twig_SimpleFilter('var_dump', function($data){
		return var_dump($data);
	}));

	return $view;
};
$container[PDO::class] = function($c){
	$db = new PDO("mysql:dbname=".getenv('MYSQL_DB_NAME').";host=".getenv('MYSQL_DB_SERVER'), getenv('MYSQL_DB_USERNAME'), getenv('MYSQL_DB_PASSWORD'));
	return $db;
};
$container[Medoo\Medoo::class] = function($c){
	$config['database_type'] = 'mysql';
	$config['database_name'] = getenv('MYSQL_DB_NAME');
	$config['server'] = getenv('MYSQL_DB_SERVER');
	$config['username'] = getenv('MYSQL_DB_USERNAME');
	$config['password'] = getenv('MYSQL_DB_PASSWORD');
	$db = new Medoo\Medoo($config);

	return $db;
};
$container[Helper\MonthHelper::class] = function($c){
	$obj = new Helper\MonthHelper();
	return $obj;
};