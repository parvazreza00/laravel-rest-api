<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class UserApiController extends Controller
{
    public function showUsers($id=null){
        if($id==''){
            $users = User::all();
            return response()->json(['users' => $users], 200);
        }else{
            $users = User::find($id);
            return response()->json(['users' => $users], 200);
        }
    }//end method

    public function addUsers(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users|max:50|min:5',
            'password' => 'required|unique:users',
        ]);
        User::insert([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        return response("Users data inserted successfully");
    }//end method
    
    public function addMultiUsers(Request $request){
        $multiusers = $request->all();
        $request->validate([
            'users.*.name' => 'required',
            'users.*.email' => 'required|unique:users|max:50|min:5',
            'users.*.password' => 'required|unique:users',
        ]);
        foreach($multiusers['users'] as $multiuser){
            $user = new User();
            $user->name = $multiuser['name'];
            $user->email = $multiuser['email'];
            $user->password = bcrypt($multiuser['password']);
            $user->save();
        }
        
        return response("Users multidata inserted successfully");
    }//end method

    public function updateUsers(Request $request, $id){
        $request->validate([
            'name' => 'required',            
            'password' => 'required|unique:users',
        ]);
        User::findOrFail($id)->update([
            'name' => $request->name,            
            'password' => bcrypt($request->password),
        ]);
        return response("Users data Updated successfully");
    }//end method

    public function updateUsersData(Request $request, $id){
        $request->validate([
            'name' => 'required',                  
        ]);
        User::findOrFail($id)->update([
            'name' => $request->name,            
        ]);
        return response("Users data name only Updated successfully");
    }//end method

    public function UsersDelete($id=null){
        User::findOrFail($id)->delete();
        return response("User data deleted successfully");
    }//end method

    //delete multiple users
    public function MultipleUsersDelete($ids){
        $ids = explode(',',$ids);
        User::whereIn('id',$ids)->delete();        
        return response("Multi User data deleted successfully");
    }//end method

    //dlete multiple users with json format
    public function MultipleUsersDeleteJson(Request $request){
            $header = $request->header('Authorization');  
            if($header==''){
                return response("Authorization is required");
            }else{
                if($header=="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IndlYiBqb3VybmV5IiwiaWF0IjoxNTE2MjM5MDIyfQ.NFYDeJzZHUap1hiZPVKqlnWinXmTumJ-tTiuk_Pzttc"){
                    $dataid = $request->all();
                    User::whereIn('id',$dataid['ids'])->delete();  
                    return response('User data is deleted successfully');  
                }else{
                    return response("Authorization token is missmatched");
                }
            }                     
    }//end method

    //register api using passport
    public function registerUserUsingPassport(Request $request){
        $data = $request->all();
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users|max:50|min:5',
            'password' => 'required|unique:users',
        ]);
        User::insert([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        if(Auth::attempt(['email' => $data['email'], 'password' => $data['password']])){
            $user = User::where('email',$data['email'])->first();
            $access_token = $user->createToken($data['email'])->accessToken;
            User::where('email',$data['email'])->update(['access_token' => $access_token]);

            return response()->json(['message'=>"User Successfully Registered", 'access_token'=>$access_token]);
        }else{
            return response("OPPS! Something went wrong man!");
        }
        
    }

    //login api using passport
    public function loginUserUsingPassport(Request $request){
        $data = $request->all();
        $request->validate([            
            'email' => 'required|exists:users',
            'password' => 'required',
        ]);
        
        if(Auth::attempt(['email' => $data['email'], 'password' => $data['password']])){
            $user = User::where('email',$data['email'])->first();
            $access_token = $user->createToken($data['email'])->accessToken;
            User::where('email',$data['email'])->update(['access_token' => $access_token]);
            return response()->json(['message'=>"User Successfully Login", 'access_token'=>$access_token]);
        }else{
            return response("Invalid email or password");
        }
        
    }
}
