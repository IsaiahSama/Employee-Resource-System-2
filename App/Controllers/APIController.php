<?php

namespace App\Controllers;

use App\Controllers\Responses\APIResponse;

class APIController {

    public function getLoginInformation(){
        header("Content-Type: application/json");
        echo json_encode(["message" => "To log in, pass `email` and `password` as body for a post request to this same route!"]);
    }

    public function authenticate(){

        APIResponse::handleSubmission();
    }
}