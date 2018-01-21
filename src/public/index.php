<?php
require "../../vendor/autoload.php";
error_reporting(E_ALL ^ E_NOTICE);

$settings = require '../settings.php';

$app = new \Slim\App($settings);

require '../dependencies.php';
require '../routes.php';

$app->run();