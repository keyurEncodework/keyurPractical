<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request){
        $title = "Login";

        return view('auth/auth',compact('title'));
    }

    public function register(Request $request){
        $title = "Signup";

        return view('auth/auth',compact('title'));
    }

    public function SignInWithGoogle(Request $request){

        $validator = Validator::make($request->all(),[
            'token' => 'required',
        ]);;

        if($validator->fails()){
            return response()->json([
                'status' => 0,
                'statusCode' => 400,
                'msg' => 'The request could not be understood by the server due to malformed syntax',
                'data' => $validator->errors(),
            ]);
        }else{
            $accessToken = $request->token;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/oauth2/v3/userinfo");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer $accessToken"
            ]);
            $response = curl_exec($ch);
            curl_close($ch);

            $userInfo = json_decode($response, true);

            // print_r($userInfo);die;
            if (isset($userInfo['email'])) {
                // Success — $userInfo contains email, sub (social_id), name, etc.

                $social_id = $userInfo['sub'];
                $email = $userInfo['email'];
                $username = str_replace(' ', '',strtolower($userInfo['name']));
                $avtar = $userInfo['picture'];

                $user = Admin::where('social_id', $social_id)->first();

                if($user){
                    $tokenResult = $user->createToken('Personal Access Token');
                    $token = $tokenResult->plainTextToken;
                    
                    $user->session_id = $token;
                    $user->save();

                    Session::setId('token',$token);
                }else{
                    $user = Admin::where('email', $email)->first();

                    if($user){
                        return response()->json([
                            'status' => 0,
                            'statuscode' => 401,
                            'msg' => "This email is already in use",
                        ]);
                    }

                    $user = new Admin();
                    $user->username = $username;
                    $user->email = $email;
                    $user->social_id = $social_id;
                    $user->password = '';
                    $user->avatar = $avtar;
                    
                    $tokenResult = $user->createToken('Personal Access Token');
                    $token = $tokenResult->plainTextToken;
                    
                    $user->session_id = $token;
                    $user->save();

                    Session::setId('token',$token);
                }

                return response()->json([
                    'status' => 1,
                    'statusCode' => 200,
                    'msg' => 'Login successfull',
                ]);
            } else {
                // Invalid token or error
                return response()->json(['error' => 'Invalid access token'], 401);
            }
        }
    }

    public function signUp(Request $request){
        $validator = Validator::make($request->all(),[
            'username' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if ($value !== strtolower($value)) {
                        $fail('The ' . $attribute . ' must be all lowercase.');
                    }
                },
            ],
            'email' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if ($value !== strtolower($value)) {
                        $fail('The ' . $attribute . ' must be all lowercase.');
                    }
                },
            ],
            'password' => [
                'required',
                'min:6',
                'max:6',
            ],
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 0,
                'statuscode' => 400,
                'msg' => 'Validation faild',
                'data' => $validator->errors(),
            ]);
        }else{

            $existingEmail= Admin::where('email', $request->email)->first();

            if($existingEmail){
                return response()->json([
                    'status' => 0,
                    'statuscode' => 401,
                    'msg' => "This email is already in use",
                ]);
            }

            $existingUserName = Admin::where('username', $request->username)->first();

            if($existingUserName){
                return response()->json([
                    'status' => 0,
                    'statuscode' => 401,
                    'msg' => "This username is already in use",
                ]);
            }

            $admin = new Admin();
            $admin->username = $request->username;
            $admin->email = $request->email;
            $admin->password = Hash::make($request->password);
            $admin->save();

            return response()->json([
                'status' => 1,
                'statuscode' => 200,
                'msg' => "Registration successfully",
            ]);
        }
    }

    public function dashboard(Request $request){
        $title = "Dashbaord";

        return view('web/dashboard',compact('title'));
    }
}
