<?php

namespace App\Controllers;

use App\Core\View;
use App\Model\User;

class AuthController
{
    public function login()
    {
        if (isset($_SESSION['user'])) {
            header('Location: /dashboard');
            exit;
        }
        
        return View::render('login');
    }

    public function loginProcess()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
            $password = $_POST['password'] ?? '';

            $userModel = new User();
            $user = $userModel->findByUsername($username);

            if ($user && password_verify($password, $user['password'])) {

                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'nama' => $user['nama'] ?? $user['username'],
                    'role' => $user['role'] ?? 'santri'
                ];

                header('Location: /dashboard');
                exit;
            }

            return View::render('login', ['error' => 'Username atau password salah!']);
        }
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        
        header('Location: /login');
        exit;
    }
}
