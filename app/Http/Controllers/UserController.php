<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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
}
