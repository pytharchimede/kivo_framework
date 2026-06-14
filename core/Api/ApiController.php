<?php
namespace Core\Api;
use Core\Http\Controller;
abstract class ApiController extends Controller{
 protected string $repository; protected string $resource;
 public function index():string{$items=(new $this->repository())->all(); return $this->json(['data'=>array_map([$this->resource,'make'],$items)]);} 
 public function show(int|string $id):string{$item=(new $this->repository())->find($id); return $this->json(['data'=>$item?($this->resource)::make($item):null],$item?200:404);} 
 public function store():string{$item=(new $this->repository())->create($_POST); return $this->json(['message'=>'Créé','data'=>($this->resource)::make($item)],201);} 
 public function update(int|string $id):string{$item=(new $this->repository())->update($id,$_POST); return $this->json(['message'=>'Mis à jour','data'=>($this->resource)::make($item)]);} 
 public function destroy(int|string $id):string{(new $this->repository())->delete($id); return $this->json(['message'=>'Supprimé']);}}
