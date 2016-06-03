<?php

namespace ExpressPHP;

use Closure;
use Exception;
use ReflectionClass;
use React\EventLoop\Factory;
use React\Socket\Server as SocketServer;
use React\Http\Server as HTTPServer;
use React\HTTP\Request as HTTPRequest;
use React\HTTP\Response as HTTPResponse;
use FastRoute\simpleDispatcher;
use FastRoute\RouteCollector;
use FastRoute\Dispatcher;
use Pimple\Container;
use Twig_Loader_Filesystem;
use Twig_Environment;

class App {
    
    private $settings = [];
    
    public function __construct(){
        $this->initContainer();
        
    }
    
    public function initContainer(){
        $this->container = new Container();
        // register defaults
        $this->container['twig'] = function ($c) {
            $views = $this->settings['views'];
            $loader = new Twig_Loader_Filesystem($views);
            return new Twig_Environment($loader, array(
                //'debug' => true
            ));
        };  
    }
    
    protected function lazyRouter(){
        if(!isset($this->_router)){
            $this->_router = new Router();
        }
        return $this->_router;
    }
    
    public function mount($mountPath="/", $router){
        // $reflector = new ReflectionClass($routeClass);
        // if(!$reflector->isSubclassOf(Router::class)){
        //     throw new Exception('Router is not an instanceof ExpressPHP\Router');
        // }
        
        $this->lazyRouter();
        if($router instanceof Router)
            $this->_router->mount($mountPath, $router);
            
        else if($router instanceof Closure){
            
        }
        
    }
    
    public function serveStatic($baseDir) {
        
        return function($req, $res, $next) {
            echo $req->getPath();
            return $next();
        };
    }
    
    public function run($port = 80, $host="0.0.0.0") {
        $router = $this->_router;
        

        $this->dispatcher = \FastRoute\simpleDispatcher(function(RouteCollector $r) use($router){
            foreach($router->_stack as $route){
                $r->addRoute($route->method, $route->path, $route->handle);
            }
        });
        // print_r($this->dispatcher );
        
        $app = function (HTTPRequest $req, HTTPResponse $res) {
            
            // while(/*match a route*/ || /*match with a dispatcher*/){
                
            // }
        
            $res = new Response($res, $this);
            
            $routeInfo = $this->dispatcher->dispatch($req->getMethod(), $req->getPath());
            // var_dump($routeInfo);
            switch ($routeInfo[0]) {
                case Dispatcher::NOT_FOUND:
                    $res->writeHead(404);
                    $res->end("Not Found");
                    break;
                case Dispatcher::METHOD_NOT_ALLOWED:
                    $res->writeHead(405);
                    $res->end("Method Not Allowed");
                    break;
                case Dispatcher::FOUND:
                    $handle = $routeInfo[1];
                    $vars = $routeInfo[2];
                    $req->params = $vars;
                    $handle($req, $res);
                    break;
            }
        };
        
        $this->loop = Factory::create();
        $this->socket = new SocketServer($this->loop);
        $this->http = new HTTPServer($this->socket);
        
        $this->http->on('request', $app->bindTo($this));
        $this->socket->listen($port, $host);
        $this->loop->run();
        return $this->http;
    }
    
    public function __call($name, $arguments)
    {
        return $this->_router->$name($arguments[0], $arguments[1]);
    }
    
    public function set($prop, $val){
        $this->settings[$prop] = $val;
    }
}
