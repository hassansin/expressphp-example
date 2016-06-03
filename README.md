# A ReactPHP framework inspired by ExpressJS (Work in Progress)

## Example 

```php

<?php
require_once __DIR__.'/../vendor/autoload.php';

use ExpressPHP\App as Express;
use ExpressPHP\Router as Router;

$app = new Express();

// set views directory
$app->set('views', __DIR__.'/app/views');

// Define Routes
$router = new Router();
        
$router->get('/', function($req, $res){
   $res->render('todo/list.html.twig'); 
});

$router->get('/{id}', function($req, $res){
    // print_r($req->params);
    $res->render('todo/item.html.twig'); 
});


$app->mount('/todo', $routes);

// 404  
$app->get('/{id:.*}', function($req, $res){
    $res->setStatus(404)->render('404.html.twig');
});


$app->run(3000, '127.0.0.1');

```

## Dependencies

react/react
nikic/fast-route
pimple/pimple
twig/twig

* react/react 
* pimple/pimple[Dependency Injection Container]
* nikic/fast-route [routing]
* twig/twig [template engine]

