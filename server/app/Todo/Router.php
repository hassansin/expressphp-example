<?php

namespace App\Todo;
use ExpressPHP\Router as ExpressRouter;



class Router extends ExpressRouter{
    public static function routes(){
        $router = new Router();
        
        $router->get('/', function($req, $res){
           $res->render('todo/list.html.twig'); 
        });
        $router->get('/{id}', function($req, $res){
            // print_r($req->params);
            $res->render('todo/item.html.twig'); 
        });
        return $router;
    }
}


