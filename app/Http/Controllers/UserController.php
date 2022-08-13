<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Auth;

class UserController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required|min:8',
        ]);
        if($validator->fails()){
            return response()->json(['status' => 'Fails', 'validation_errors' => $validator->errors()]);
        }

        $data = $request->all();
        $data['password'] = Hash::make($request->password);

        $user = User::create($data);
        if($user){
            return response()->json(['status' => 'success', 'message' => 'User registration created successfully', 'data' => $user]);
        }else{
            return response()->json(['status' => 'Fail', 'message' => 'User registration fails']);
        }
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|min:8',
        ]);
        if($validator->fails()){
            return response()->json(['status' => 'Fails', 'validation_errors' => $validator->errors()]);
        }

        //login
        if(Auth::attempt(['email' =>$request->email, 'password' => $request->password])){
            $user = Auth::user();
            $access_token = $user->createToken('accesstoken')->accessToken;

            return response()->json(['status' => 'success', 'message' => "Login success", 'token' => $access_token, 'data' => $user]);
        }else{
            return response()->json(['status' => 'Fails', 'message' => "OPPS! Email or Password invalid"]);
        }        
    }
    
    public function userDetails(){
        $user = Auth::user();
        if($user){
            return response()->json(['status' => 'Success', 'user' => $user]);
        }else{
            return response()->json(['status' => 'Fails']);
        }
    }
}

