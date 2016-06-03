<?php
require_once __DIR__.'/../vendor/autoload.php';
error_reporting(-1);

use ExpressPHP\App as Express;

$app = new Express();

$app->set('views', __DIR__.'/app/views');
//$app->mount('/', $app->serveStatic(__DIR__.'/../client'));

// Todo module endpoints
$app->mount('/todo', \App\Todo\Router::routes());

// 404 
$app->get('/{id:.*}', function($req, $res){
    $res->setStatus(404)->render('404.html.twig');
});

$app->run(8080);