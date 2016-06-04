<?php
require_once __DIR__.'/../vendor/autoload.php';
error_reporting(-1);

use ExpressPHP\App as Express;

$app = new Express();

$app->set('views', __DIR__.'/app/views');
$app->serveStatic(__DIR__.'/../client');

// Todo module endpoints
$app->mount('/todo', \App\Todo\Router::routes());

$app->get('/', function($req, $res){
   $res->render('home.html.twig');
});

$app->run(8080);