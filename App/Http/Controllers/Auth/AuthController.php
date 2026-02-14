<?php
namespace App\Http\Controllers\Auth;

use App\Services\Auth\AuthService;
use App\Core\BaseController;
use App\Services\User\UserService;

class AuthController extends BaseController {
    
    public function login() {
        $auth = new AuthService();
        $userSvc = new UserService();

        if ($auth->login($_POST['email'] ?? '', $_POST['password'] ?? '')) {
            // Start session if not already started
            if (session_status() === PHP_SESSION_NONE) session_start();

            // Set session data
            $_SESSION['is_logged_in'] = true;
            $userData = $userSvc->getUserByEmail($_POST['email']);
            $_SESSION['user_id'] = $userData['id'];
            $_SESSION['username'] = $userData['username'];

            // MANDATORY: Save session data before redirecting
            session_write_close();

            wp_safe_redirect(home_url('/genealogy/genealogy-dashboard/'));
            exit; 
        } else {
            $this->renderView('Auth/login', ['errorMessage' => 'Invalid Credentials']);
        }
    }

    public function showLoginPage() {
        if (!empty($_SESSION['is_logged_in'])) {
            wp_safe_redirect(home_url('/genealogy/genealogy-dashboard/'));
            exit;
        }
        $this->renderView('Auth/login');
    }

    public function logout() {
        session_destroy();
        wp_safe_redirect(home_url('/genealogy/loginpage/'));
        exit;
    }
}