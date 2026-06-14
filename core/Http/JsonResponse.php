<?php
namespace Core\Http;
final class JsonResponse{public static function send(array $data,int $status=200):string{http_response_code($status); header('Content-Type: application/json; charset=utf-8'); return json_encode($data,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);}}
