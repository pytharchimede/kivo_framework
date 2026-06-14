<?php
namespace App\Controllers;

use Core\Http\Controller;

final class AuthController extends Controller
{
    public function login(): string
    {
        return $this->view('auth.login', ['title' => 'Connexion']);
    }

    public function attempt(): string
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $email = trim((string) ($_POST['email'] ?? ''));
        $_SESSION['kivo_user'] = [
            'name' => $email !== '' ? $email : 'Administrateur',
            'email' => $email !== '' ? $email : 'admin@demo.local',
        ];

        header('Location: /portal');
        return '';
    }

    public function logout(): string
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        unset($_SESSION['kivo_user']);
        header('Location: /login');
        return '';
    }
}
