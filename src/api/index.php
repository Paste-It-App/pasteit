<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
////////////////// Index file //////////////
/// Creates base routes and runs         ///
/// respective functions                 ///
////////////////////////////////////////////
require "../../vendor/autoload.php";
require "utils/middleware.php";
require "paste/pasteRoutes.php";

use api\paste\pasteRoutes;
use api\utils\middleware;
use Slim\Factory\AppFactory;

// Start slim
$app = AppFactory::create();

// set base path for all routes
$app->setBasePath("/api");

// Add middleware
new middleware($app);

new pasteRoutes($app);

$app->run();