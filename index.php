<?php
require "vendor/autoload.php";
error_reporting(E_ALL ^ E_NOTICE);

$settings = require 'src/settings.php';

$app = new \Slim\App($settings);

require 'src/dependencies.php';
require 'src/routes.php';

$app->run();
