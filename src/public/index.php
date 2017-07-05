<?php
// php -S localhost:8080
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../../vendor/autoload.php';

$settings = require '../settings.php';

$app = new \Slim\App($settings);

require '../dependencies.php';
require '../routes.php';

$app->run();
