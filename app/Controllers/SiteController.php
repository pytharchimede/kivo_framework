<?php
namespace App\Controllers;
use Core\Http\Controller;

final class SiteController extends Controller
{
    public function home(): string
    {
        return $this->view('site.home', ['title' => 'Site web']);
    }
}
