<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserApiController;
use App\Http\Controllers\UserController;

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
//delete api for deleting single data
Route::delete('/users-delete/{id}', [UserApiController::class, 'UsersDelete']);
//delete api for deleting multiple data
Route::delete('/multiple-users-delete/{id}', [UserApiController::class, 'MultipleUsersDelete']);
//delete api for deleting multiple data with json
Route::delete('/multiple-users-delete-json', [UserApiController::class, 'MultipleUsersDeleteJson']);
//laravel passport api auth
Route::post('/register-api-using-passport', [UserApiController::class, 'registerUserUsingPassport']);
Route::post('/login-api-using-passport', [UserApiController::class, 'loginUserUsingPassport']);


//routing for userController 
Route::post('/register',[UserController::class, 'register']);
Route::post('/login',[UserController::class, 'login']);
Route::middleware('auth:api')->group(function () {
         Route::get('/user',[UserController::class, 'userDetails']);
    });