<?php 

namespace ExpressPHP;

use React\Http\Response as HTTPResponse;

class Response {
    
    // Override parent constructor
    // $conn property will refer to same object because of shallow clone
    public function __construct(HTTPResponse $response, App $app){
        $this->httpResponse = $response;
        $this->app = $app;
        $this->status = 200;
        $this->headers = [
            'Content-Type' => 'text/html',
        ];
    }
    
    public function __call($name, $arguments){
        return call_user_func_array(array($this->httpResponse, $name), $arguments);
    }
    
    public function setStatus($status){
        $this->status = $status;
        return $this;
    }
    public function setHeaders($headers = []){
        $this->headers = array_merge($this->headers, $headers);
        return $this;
    }
    public function render($template, $args = []){
        $this->writeHead($this->status, $this->headers);
        $html =  call_user_func_array(array($this->app->container['twig'], 'render'), [$template, $args]);
        $this->write($html);
        $this->end();
    }
}