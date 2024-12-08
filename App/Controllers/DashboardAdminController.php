<?php 

namespace App\Controllers;

use App\Helpers\AuthHelper;
use App\Controllers\Responses\UserResponse;

use Framework\Controllers\ViewController;
use Framework\Controllers\SessionValues;
use Framework\Controllers\Authentication;
use Framework\Views\Layout;

class DashboardAdminController implements ViewController {

    public function guard() {
        Authentication::hasAccess(SessionValues::USER_INFO->value, function ($string, $criteria) {
            return AuthHelper::hasAccess($string, $criteria);
        }, AuthHelper::forAdmin());
    }

    public function index() {
        $this->guard();

        header("HTTP/1.0 200 OK");
        Layout::render("dashboards/admin_dashboard", ['header' => 'App/Views/partials/dashboardHeader.php', 'title' => "Admin Dashboard"]);
    }

    
    public function editUser() {
        
        $this->guard();
        
        header("HTTP/1.0 200 OK");
        Layout::render("users/edit", ['title' => "Edit User"]);
    }
    
    public function deleteUser() {

        $this->guard();

        UserResponse::deleteUser();
    }

    public function adminUpdateUser() {

        $this->guard();

        UserResponse::updateUser();
        
    }
}