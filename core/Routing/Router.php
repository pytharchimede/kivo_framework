<?php
namespace Core\Routing;
final class Router{
 private array $routes=[];
 public function get(string $uri, callable|array $action):void{$this->add('GET',$uri,$action);} public function post(string $uri, callable|array $action):void{$this->add('POST',$uri,$action);} public function apiResource(string $name,string $controller):void{ $b='/api/'.trim($name,'/'); $this->get($b,[$controller,'index']); $this->post($b,[$controller,'store']); $this->get($b.'/{id}',[$controller,'show']); $this->post($b.'/{id}',[$controller,'update']); $this->post($b.'/{id}/delete',[$controller,'destroy']);}
 private function add(string $method,string $uri, callable|array $action):void{$this->routes[]=[$method,$this->normalize($uri),$action];}
 public function dispatch(string $uri,string $method):void{$path=$this->normalize(parse_url($uri,PHP_URL_PATH)?:'/'); foreach($this->routes as [$m,$r,$a]){if($m!==strtoupper($method))continue; $p=$this->match($r,$path); if($p!==null){$this->call($a,$p);return;}} http_response_code(404); echo '404';}
 private function call(callable|array $action,array $params):void{ if(is_callable($action)){echo $action(...$params);return;} [$class,$method]=$action; echo (new $class())->$method(...$params);}
 private function match(string $route,string $path):?array{$names=[];$pattern=preg_replace_callback('/\\{([a-zA-Z_][a-zA-Z0-9_]*)\\}/',function($m)use(&$names){$names[]=$m[1];return '([^/]+)';},preg_quote($route,'#')); if(!preg_match('#^'.$pattern.'$#',$path,$m))return null; array_shift($m); return array_map('rawurldecode',$m);} private function normalize(string $uri):string{$uri='/'.trim($uri,'/'); return $uri==='/'?'/':rtrim($uri,'/');}}
