<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Post;
use Auth;

class PostController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $authuser = Auth::user();
        if($authuser){
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'description' => 'required',
                
            ]);
            if($validator->fails()){
                return response()->json(['status' => 'Fails', 'validation_errors' => $validator->errors()]);
            }

            $data = $request->all();
            $data['user_id'] = auth()->id();

            $post = Post::create($data);
            if($post){
                return response()->json(['status' => 'success', 'message' => 'Post created successfully', 'post' =>$post]);
            }else{
                return response()->json(['status' => 'Fails', 'message' => 'Post created Fails']);
            }
        }else{
            return response()->json(['status' => 'Fails' , 'UnAuthorized' => '403']);
    }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
