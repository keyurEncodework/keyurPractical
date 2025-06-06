<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
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
                    
                    $user->session_id = Session::getId();
                    $user->save();

                    // Session::setId('token',$token);
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
                    
                    $user->session_id = Session::getId();
                    $user->save();

                    // Session::setId('token',$token);
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

    public function signIn(Request $request){
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

            $admin = Admin::where('username',$request->username)->first();

            if($admin && Hash::check($request->password, $admin->password)){
                $admin->session_id = Session::getId();
                $admin->save();

                return response()->json([
                    'status' => 1,
                    'statuscode' => 200,
                    'msg' => "Login successfully",
                ]);
            }else{
                return response()->json([
                    'status' => 0,
                    'statuscode' => 400,
                    'msg' => 'Username or Password are incorrect',
                ]);
            }

        }
    }

    public function dashboard(Request $request){
        $title = "Dashbaord";

        $sessionId = Session::getId();
        $admin = Admin::where('session_id',$sessionId)->first();
        $avatar = $admin ? $admin->avatar : 'default.png';

        $time = date('H');

        if($time < 12){
            $message = "Good Morning";
        }else if($time >= 12 || $time <= 5){
            $message = "Good Afternoon";
        }else if($time >= 5 || $time <= 8){
            $message = "Good Evening";
        }else{
            $message = "Good To See you";
        }

        return view('web/dashboard/dashboard',compact('title', 'avatar', 'message'));
    }

    public function users(Request $request){
        $title = "Users";

        return view('web/user/index', compact('title'));
    }

    public function usersAjax(Request $request){
        $selectColumns = [
            'users.id',
            'users.first_name',
            'users.last_name',
            'users.email',
            'users.mobile_number',
            'users.birth_date',
            'addresses.id as address_id',
            'addresses.user_id',
            'addresses.address',
            'addresses.city',
            'addresses.state',
            'addresses.type',
        ];

        $sortingColumns = [
            0 => 'users.id',
            1 => 'users.first_name',
            1 => 'users.last_name',
            2 => 'users.email',
            3 => 'users.mobile_number',
            4 => 'users.birth_date',
        ];

        $searchingColumns = [
            'users.id',
            'users.first_name',
            'users.last_name',
            'users.email',
            'users.mobile_number',
            'users.birth_date',
        ];

        $query = User::query();
        $query->select($selectColumns);
        $query->leftJoin('addresses','addresses.user_id','users.id');

        $recordsTotal = $query->count();

        if(isset($request['search']['value'])){
            $searchValue = $request['search']['value'];

            $query->where(function ($query) use ($searchValue, $searchingColumns){
                for($i = 0; $i < count($searchingColumns); $i++){
                    $query->orWhere($searchingColumns[$i],'like', '%' . $searchValue . '%');
                }
            });
        }

        $recordsFiltered = $query->count();

        $query->orderBy($sortingColumns[$request['order'][0]['column']], $request['order'][0]['dir']);
        $query->limit($recordsTotal);
        $query->offset($request['start']);

        $data = $query->get();
        $data = json_decode(json_encode($data, true));
        
        $viewData = array();
        
        foreach($data as $key => $value){

            print_r($value->birth_date);
            echo "\n";
            // $viewData[$key] = array();
            // $viewData[$key]['id'] = $value->id;
            // $viewData[$key]['full_name'] = $value->first_name . $value->last_name;
            // $viewData[$key]['email'] = $value->email;
            // $viewData[$key]['mobile_no'] = $value->mobile_number;
            // $viewData[$key]['date_of_birth'] = date('d-m-Y',$value->birth_date);
            // $viewData[$key]['address'] = "<button class='btn btn-success'>View</button>";
            // $viewData[$key]['action'] = "<button class='btn btn-primary'>Edit</button><button class='btn btn-danger'>Delete</button>";
        }

        // print_r($viewData);
        die;

           
            if($request->filled('searchQuery')){
                $query->search($request->searchQuery);
            }

            if($request->filled('minAge') || $request->filled('maxAge')){
                $query->ageRange($request->minAge, $request->maxAge);
            }

            if($request->filled('city')){
                $query->byCity($request->city);
            }

            $users = $query->paginate();

            
            $users->getCollection()->transform(function ($user){
                return [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'mobile_number' => $user->mobile_number,
                    'birth_date' => $user->birth_date->format('Y-m-d'),
                    'age' => $user->age,
                    'addresses' => $user->addresses,
                ];
            });

             print_r($users);
        die;
            return response()->json([
                'success' => true,
                'msg' => "users retrived successfully",
                'data' => $users,
            ]);
       
    }
}
