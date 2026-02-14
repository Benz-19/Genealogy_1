<?php
namespace App\Http\Controllers\Pages;

use App\Core\BaseController;

class PagesController extends BaseController{
    /*
     * LANDING PAGE 
     */    
    public function landingPage(){
        $this->renderView('Pages/landing'); 
    }
}