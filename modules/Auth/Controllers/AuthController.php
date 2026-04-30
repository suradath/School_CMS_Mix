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
            if ($user['status'] !== 'active') {
                $_SESSION['error'] = 'บัญชีของคุณถูกระงับการใช้งาน กรุณาติดต่อผู้ดูแลระบบ';
                $this->redirect('/auth');
            }

            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            
            // Fetch and store all roles
            $roles = User::getRoles((int)$user['id']);
            $roleSlugs = array_column($roles, 'slug');
            $_SESSION['user_roles'] = $roleSlugs;
            
            // Keep user_role for backward compatibility (using the first role or 'teacher' as default)
            $_SESSION['user_role'] = $roleSlugs[0] ?? 'teacher';
            
            $_SESSION['personnel_id'] = $user['personnel_id'];
            $_SESSION['department_id'] = $user['department_id'];
            
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
