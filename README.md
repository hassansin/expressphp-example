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

// set static file directory
$app->serveStatic(__DIR__.'/../client');


// Define Routes
$router = new Router();
        
$router->get('/', function($req, $res){
   $res->render('todo/list.html.twig'); 
});

$router->get('/{id}', function($req, $res){
    // print_r($req->params);
    $res->render('todo/item.html.twig'); 
});

// mount routes to /todo endpoint
$app->mount('/todo', $router);

$app->run(3000, '127.0.0.1');

```

A sample application is already provided. To run the app:

```
composer install
php server/server.php
```

## Dependencies

* react/react 
* pimple/pimple[Dependency Injection Container]
* nikic/fast-route [routing]
* twig/twig [template engine]
* dflydev/apache-mime-types [to server static files Content-Type header]

