<?php
namespace App\Http\Controllers\Auth;

use App\Services\Auth\AuthService;
use App\Core\BaseController;
use App\Services\User\UserService;

class AuthController extends BaseController {
    private $auth_service;
    private $user_service;

    public function __construct()
    {
        $this->auth_service = new AuthService();
        $this->user_service = new UserService();
    }

    public function showRegisterPage(){
        $this->renderView('Auth/register');
    }

    public function register(){
        // Ensure the right request is received
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->renderView('Auth/register');
                return;
        }

        try {
            if(empty($_POST['email']) || empty($_POST['username']) || empty($_POST['password'])){
                $this->renderView("Auth\register", [
                    "errorMessage" => "Ensure all fields are filled."
                ]);
            }

            // Register a new user
            $data = [
                'username' => $_POST['username'],
                'email' => $_POST['email'],
                'password' => $_POST['password']
            ];

            $referral_code = $_POST['referral_code'];
            $success = $this->auth_service->register($data, $referral_code);

            if($success){
                $this->renderView("Auth/register", [
                    "successMessage" => "Account successfully created."
                ]);                
            }else{

                // Redirect if with error message
                $this->renderView("Auth/register", [
                    "errorMessage" => "Something went wrong. Try again."
                ]);
            }            

        } catch (\Exception $error) {
            $this->renderView(
                "Auth/register",
                [
                    "errorMessage" => $error->getMessage(),
                    "oldInput" => $_POST
                ]
            );
        }
    }

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