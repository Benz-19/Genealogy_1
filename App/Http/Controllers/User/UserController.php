<?php
namespace App\Http\Controllers\User;

use App\Core\BaseController;

class UserController extends BaseController{

    public function showDashboard(){
        if(isset($_SESSION["is_logged_in"]) && $_SESSION["is_logged_in"] === true){
            $this->renderView('User/dashboard');
        }else{
            header("Location: /login");
            exit;
        }
    }
}
