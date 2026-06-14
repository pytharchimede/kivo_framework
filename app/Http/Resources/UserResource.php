<?php
namespace App\Http\Resources;
final class UserResource{public static function make(array $u):array{return ['id'=>$u['id']??null,'name'=>$u['name']??null,'email'=>$u['email']??null];}}
