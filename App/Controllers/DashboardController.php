<?php 

namespace App\Controllers;

use Framework\Controllers\ViewController;
use Framework\Views\Layout;


class DashboardController implements ViewController {

    public function guard() {}

    public function index() {

        header("HTTP/1.0 200 OK");
        Layout::render("dashboards/dashboard", ['header' => 'App/Views/partials/dashboardHeader.php', 'title' => "Generic Dashboard"]);
    }
}
