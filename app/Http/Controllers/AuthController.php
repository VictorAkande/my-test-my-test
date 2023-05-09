<?php

namespace App\Http\Controllers;

use App\CustomHelper\HelperClasses\ApiResponse;
use App\CustomHelper\HelperClasses\FileLogger;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Test API",
 *      description="My interview API description",
 *      @OA\Contact(
 *          email="akandevictor846@gmail.com",
 *          name="Victor Akande"
 *      )
 *
 * )
 */
class AuthController extends Controller
{

    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Registers a user",
     *     description="Register a user with name, email, is_admin and password",
     *     operationId="authRegister",
     *     operationId="authRegister",
     *     tags={"User Access"},
     *     @OA\RequestBody(
     *         description="Registration credentials",
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password", "is_admin", "password"},
     *             @OA\Property(property="name", type="string", format="name", example="Victor Akande"),
     *             @OA\Property(property="email", type="string", format="email", example="victorAkande@example.com"),
     *             @OA\Property(property="is_admin", type="boolean", format="is_admin", example=1),
     *             @OA\Property(property="password", type="string", format="password", example="password123"),
     *             @OA\Property(property="password_confirmation", type="string", format="confirm_password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string"),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *             ),
     *      @OA\Property(
     *                 property="data",
     *                 type="object",
     *             )
     *
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string"),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *             ),
     *      @OA\Property(
     *                 property="data",
     *                 type="object",
     *             )
     *
     *         )
     *     )
     * )
     */


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
            return ApiResponse::errorMessage('Error while Registering User', 500, null);
        }

       // $token = $user->createToken('Some-Hash')->plainTextToken;
        $response = [
            "user" => $user,
          //  "token" => $token
        ];
        FileLogger::info($response['user'], $request);
        return ApiResponse::successMessage($response,'Success', 200);
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Logs in a user",
     *     description="Logs in a user with email and password",
     *     operationId="authLogin",
     *     tags={"User Access"},
     *     @OA\RequestBody(
     *         description="Login credentials",
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="johndoe@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string"),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *             ),
     *      @OA\Property(
     *                 property="data",
     *                 type="object",
     *             )
     *
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string"),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *             ),
     *      @OA\Property(
     *                 property="data",
     *                 type="object",
     *             )
     *
     *         )
     *     )
     * )
     */

    public  function  login (Request $request){


        FileLogger::info($request->input('email'), $request );

        //Validate Request
        $fields = $request->validate([
            'email'=> 'required',
            'password'=> 'required'
        ]);

        $user = User::where('email', $fields['email'])->first();

        //Check id login Credential is Valid
        if (is_null($user) || !Hash::check($fields['password'], $user->password)){
            FileLogger::Error($request->input('email'), $request,'Invalid Login Credentials' );
            return ApiResponse::errorMessage('Invalid Login Credentials', 400, null);
        }

        $token = $user->createToken('Some-Hash')->plainTextToken;

        $response = [
            "user" => $user,
            "token" => $token
        ];
        FileLogger::info($response['user'], $request);
        return ApiResponse::successMessage($response,'Success', 200);
    }



    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="For logout",
     *     description="Logout endpoint",
     *     operationId="authLogout",
     *     tags={"User Access"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         description="Logout",
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string"),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *             ),
     *      @OA\Property(
     *                 property="data",
     *                 type="object",
     *             )
     *
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string"),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *             ),
     *      @OA\Property(
     *                 property="data",
     *                 type="object",
     *             )
     *
     *         )
     *     )
     * )
     */
    public  function logout(Request $request){
        auth()->user()->tokens()->delete();

        $response = [
            "msg" => "User Logged out Successfully",
        ];
        FileLogger::info($response['msg'], $request);
        return ApiResponse::successMessage($response,'Success', 200);
    }

    /**
     * @OA\Post(
     *     path="/api/forgotPassword",
     *     summary="For recovering forgotten password",
     *     description="Recovers using valid email",
     *     operationId="authforgotPassword",
     *     tags={"User Access"},
     *     @OA\RequestBody(
     *         description="Forgot Password credentials",
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", format="email", example="victorAkande@example.com"),
     *        )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string"),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *             ),
     *      @OA\Property(
     *                 property="data",
     *                 type="object",
     *             )
     *
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string"),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *             ),
     *      @OA\Property(
     *                 property="data",
     *                 type="object",
     *             )
     *
     *         )
     *     )
     * )
     */
    public  function forgotPassword(Request $request){

        $fields = $request->validate([
            'email' => 'required'
        ]);

        //Validate email
        $user = User::where('email', $fields['email'])->first();

        //Check email is Valid
        if (is_null($user) ){
            $response = [
                "msg" => "User Not Founds"
            ];
            return ApiResponse::errorMessage( 400, $response);
        }

        //Generate a 4 digits password reset token
        $resetToken = rand(pow(10, 4-1), pow(10, 4)-1);


        $updateResettoken = User::where('email', $fields['email'])
            ->update(['reset_token' => $resetToken]);

        if (is_null($updateResettoken) ){
            $response = [
                "msg" => "Error while Processing Request"
            ];
            return ApiResponse::errorMessage( 500, $response);
        }
        $response = [
            "msg" => "A Reset Token has been sent to you Email"
        ];
        return ApiResponse::successMessage($response,'Success', 200);
    }

    /**
     * @OA\Post(
     *     path="/api/resetPassword",
     *     summary="For resetting password",
     *     description="Resets password using valid token and new password",
     *     operationId="authresetPassword",
     *     tags={"User Access"},
     *     @OA\RequestBody(
     *         description="Reset password credentials",
     *         required=true,
     *         @OA\JsonContent(
     *             required={"token", "password"},
     *             @OA\Property(property="token", type="string", format="token", example="1234"),
     *             @OA\Property(property="new_password", type="string", format="password", example="newpassword123"),
     *             @OA\Property(property="new_password_confirmation", type="string", format="new_password_confirmation", example="newpassword123"),
     *        )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string"),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *             ),
     *      @OA\Property(
     *                 property="data",
     *                 type="object",
     *             )
     *
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string"),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *             ),
     *      @OA\Property(
     *                 property="data",
     *                 type="object",
     *             )
     *
     *         )
     *     )
     * )
     */
    public function resetPassword(Request $request){
        $fields = $request->validate([
                'token' => 'required|max:8',
                'new_password' => 'required|confirmed',
        ]);

        $token = User::where('reset_token', $fields['token'])->first();

        //Check token is Valid
        if (is_null($token)){
            $response = [
                "msg" => "Invalid Reset Token"
            ];
            FileLogger::error(null, $request, $response['msg']);
            return ApiResponse::errorMessage( 'Error' ,400, $response);
        }
        //Update Password.
        $updateResettoken = User::where('reset_token', $fields['token'])
            ->update(['password' => bcrypt($fields['new_password'])]);

        $response = [
            "msg" => "Password Reset Successfully"
        ];

        return ApiResponse::successMessage($response,'Success', 200);
    }


    /**
     * @OA\Get(
     *     path="/api/getRandomUser",
     *     summary="For getting random user",
     *     description="getRandomUser endpoint",
     *     operationId="authgetRandomUser",
     *     tags={"User Access"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         description="getRandomUser",

     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string"),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *             ),
     *      @OA\Property(
     *                 property="data",
     *                 type="object",
     *             )
     *
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string"),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *             ),
     *      @OA\Property(
     *                 property="data",
     *                 type="object",
     *             )
     *
     *         )
     *     )
     * )
     */
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
