<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//get api for fetch users
Route::get('/users/{id?}', [UserApiController::class, 'showUsers']);
//post api for add users
Route::post('/add-users', [UserApiController::class, 'addUsers']);
//post api for add multi-users or multi-products
Route::post('/add-multi-users', [UserApiController::class, 'addMultiUsers']);
//put api for update user details
Route::put('/update-users/{id}', [UserApiController::class, 'updateUsers']);
//patch api for update users table single field data
Route::patch('/update-users-data/{id}', [UserApiController::class, 'updateUsersData']);
//delete api for deleting data
Route::delete('/users-delete/{id}', [UserApiController::class, 'UsersDelete']);
