<?php
declare(strict_types=1);

namespace Modules\Auth\Controllers;

use Core\Controller;
use Modules\Auth\Models\User;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function index(): void
    {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/dashboard');
        }
        $this->render('Auth.Views.login');
    }

    /**
     * Handle login submission
     */
    public function login(): void
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = User::findByUsername($username);

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['user_role'] = $user['role'];
            
            User::updateLastLogin((int)$user['id']);
            $this->redirect('/dashboard');
        } else {
            $_SESSION['error'] = 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง';
            $this->redirect('/auth');
        }
    }

    /**
     * Handle logout
     */
    public function logout(): void
    {
        session_destroy();
        $this->redirect('/auth');
    }
}
