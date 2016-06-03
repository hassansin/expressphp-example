<?php
namespace ExpressPHP;

class Route{
    
    private $method, $path, $handle;
    
    public function __construct($method, $path, $handle){
        $this->method = $method;
        $this->path = $path;
        $this->handle = $handle;
    }
    
    public function prepend($path){
        $this->path = implode('/', array(
            rtrim($path, '/'), ltrim($this->path, '/')    
        ));
    }
    public function __get($property) {
        if (property_exists($this, $property)) {
          return $this->$property;
        }
    }
}