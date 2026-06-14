<?php
namespace Core\Http;
use Core\View\View;
use Core\Http\JsonResponse;
abstract class Controller{protected function view(string $view,array $data=[]):string{return View::render($view,$data);} protected function json(array $data,int $status=200):string{return JsonResponse::send($data,$status);}}
