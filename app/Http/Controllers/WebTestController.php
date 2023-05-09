<?php

namespace App\Http\Controllers;

use App\CustomHelper\HelperClasses\ApiResponse;
use App\CustomHelper\HelperClasses\FileLogger;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class WebTestController extends Controller
{
    //

    public  function  login (Request $request){


        FileLogger::info($request->input('email'), $request );

        //Validate Request
        $fields = $request->validate([
            'email'=> 'required',
            'password'=> 'required'
        ]);

        $cred = $request->only('email', 'password');

        if (Auth::attempt($cred)){
            return redirect()->route('dashboard')->with('success', 'you have successfully logged in');
        }

        return redirect()->route('login')->with('error', 'Invalid email or password');


    }

    public  function  register (Request $request){


        FileLogger::info($request->input('email'), $request );
        //Validate Request
        $fields = $request->validate([
            'name'=> 'required',
            'email'=> 'required|unique:users,email',
            'is_admin'=> 'required',
            'password'=> 'required|confirmed',
        ]);
        //Log request


        $user = User::create([
            'name' =>$fields['name'],
            'email' =>$fields['email'],
            'is_admin' =>$fields['is_admin'],
            'password' =>bcrypt($fields['password']),
        ]);

        if ($user == null){
            FileLogger::error($request->input('email'), $request);
            //return ApiResponse::errorMessage('Error while Registering User', 500, null);

            return redirect()->route('login')->with('error', 'Error while Registering User');
        }

        $response = [
            "user" => $user,
        ];
        FileLogger::info($response['user'], $request);
        return redirect()->route('login')->with('info', 'Registration successful, now you can login');;
    }


    public function dashboard(){
        if (Auth::check()){

            $role = Auth::user()->is_admin === 1 ? "Admin" : "User" ;
            return view('dashboard')->with('role', $role  );
        }
        return redirect()->route('login')->with('error', 'You need to log in first');
    }

    public function logout(){
      Session::flush();
        Auth::logout();
        return redirect()->route('login')->with('success', 'you have logged out successfully');
    }
}

