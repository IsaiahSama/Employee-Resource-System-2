<?php 

namespace App\Controllers;

use App\Helpers\AuthHelper;
use Framework\Controllers\ViewController;
use Framework\Views\Layout;
use Framework\Controllers\SessionValues;
use Framework\Controllers\Authentication;

class DashboardEmployeeController implements ViewController {

    public function guard() {
        Authentication::hasAccess(SessionValues::USER_INFO->value, function ($string, $criteria) {
            return AuthHelper::hasAccess($string, $criteria);
        }, AuthHelper::forEmployee());
    }

    public function index() {
        // Validate if the user is logged in
        $this->guard();
        
        header("HTTP/1.0 200 OK");
        Layout::render("dashboards/employee_dashboard", ['header' => 'App/Views/partials/dashboardHeader.php', 'title' => "Employee Dashboard"]);
    }
}