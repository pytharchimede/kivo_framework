<?php
namespace Core\Database;
abstract class Repository{protected string $table; public function all():array{return [];} public function find(int|string $id):?array{return ['id'=>$id,'name'=>'Demo'];} public function create(array $data):array{return ['id'=>1]+$data;} public function update(int|string $id,array $data):array{return ['id'=>$id]+$data;} public function delete(int|string $id):void{}}
