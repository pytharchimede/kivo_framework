<?php
namespace Core\Http;
use Core\Routing\Router;
final class Application{public function __construct(private Router $router){} public function run():void{$this->router->dispatch($_SERVER['REQUEST_URI']??'/',$_SERVER['REQUEST_METHOD']??'GET');}}
