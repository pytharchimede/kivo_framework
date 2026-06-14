<?php
namespace App\Controllers;
use Core\Http\Controller;

final class PortalController extends Controller
{
    public function index(): string
    {
        return $this->view('portal.index', ['title' => 'Portail de sélection']);
    }
}
