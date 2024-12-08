<?php 

namespace App\Controllers;

use App\Helpers\AuthHelper;

use Framework\Controllers\Authentication;
use Framework\Controllers\ViewController;
use Framework\Controllers\SessionValues;
use Framework\Views\Layout;

class DashboardManagerController implements ViewController{

    public function guard(){
        Authentication::hasAccess(SessionValues::USER_INFO->value, function ($string, $criteria) {
            return AuthHelper::hasAccess($string, $criteria);
        }, AuthHelper::forManager());
    }

    public function index() {
        // Validate if the user is logged in
        $this->guard();

        header("HTTP/1.0 200 OK");
        Layout::render("dashboards/manager_dashboard", ['header' => 'App/Views/partials/dashboardHeader.php', 'title' => "Manager Dashboard"]);
    }
}