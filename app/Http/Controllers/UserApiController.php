<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

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
}
