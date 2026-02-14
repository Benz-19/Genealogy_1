<?php
namespace App\Http\Controllers\User;

use App\Core\BaseController;

class UserController extends BaseController {
    public function showDashboard() {
        // If the session didn't save, this is why you are redirected
        if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
            // Log for debugging (check your debug.log)
            error_log('Access denied to dashboard: Session not set.');
            
            wp_safe_redirect(home_url('/genealogy/loginpage/'));
            exit;
        }

        $this->renderView('User/dashboard');
    }
}