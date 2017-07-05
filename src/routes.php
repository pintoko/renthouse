<?php
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\ResponseInterface;

$app->get('/hello/{name}', function ($request, $response, $args){
	$name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name");

    return $response;
});

$app->get('/ancol', function($request, $response, $args){
	$headers = $request->getHeaders();
	$header = $request->getHeader('Host');
	return $response->withStatus(200)->write('Hello World');
});

$app->post('/ancol', function($request, $response, $args){
	$headers = $request->getHeaders();
	$header = $request->getHeader('Host');
	return $response->withStatus(200)->write('Hello World');
});

$app->post('/json', function($request, $response, $args){
	$data = ['a', 'b'=>'c'];

	return $response->withHeader('content-type', 'application/json')
	->withAddedHeader('Allow', 'PUT')
	->withAddedHeader('Kaskus', 'OceOke')
	->withJson($data);
});

$app->get('/', function(ServerRequestInterface $request, ResponseInterface $response){
	return $response->getBody()->write('Halo');
});

$app->get('/coba/{name}', Controller\HelloController::class.':coba');
$app->get('/rumah', Controller\RumahController::class.':getRumah');
