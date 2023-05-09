<?php

namespace App\Http\Controllers;

use App\CustomHelper\HelperClasses\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{

    public function getRandomUser(){
        $response = Http::get('https://jsonplaceholder.typicode.com/users');

        // If the response was successful  decode the JSON data and get a random user
        if ($response->ok()) {
            $users = $response->json();
            $randomUser = $users[rand(0, count($users) - 1)];

            $name = $randomUser['name'];
            $email = $randomUser['email'];

            $userData = [
                'fullname' => $name,
                'email' => $email
            ];
            $response = [
                "randomUser" => $userData,
            ];
            return ApiResponse::successMessage($response, 'Success', 200);
        } else {

            return ApiResponse::errorMessage('Unable To Fetch User', 500, null);
        }
    }
}
