<?php
namespace App\Controllers;
use Core\Http\Controller;
final class UserController extends Controller{public function index():string{return $this->view('users.index',['title'=>'Utilisateurs']);}}
