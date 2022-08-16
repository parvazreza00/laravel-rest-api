<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Auth;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use GuzzleHttp\Promise\Utils;

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
    //concurrent Http request 
    
    public function sequential()
{
    //header("Content-Type: application/json;charset=utf-8");
    $time_start = microtime(true);
    $client = new Client();
    $responses = [];
    $responses['FlyFar'] = $client->get('https://api.flyfarint.com/v.1.0.0/AirSearch/oneway.php?tripType=oneway&journeyfrom=DAC&journeyto=DXB&departuredate=2022-08-30&adult=1&child=0&infant=0');
    $responses['DarkSky'] = $client->get('https://rapidapi.com/darkskyapis/api/dark-sky/');
    $responses['API-NBA'] = $client->get('https://rapidapi.com/api-sports/api/api-nba/');
    $responses['API-FOOTBALL'] = $client->get('https://rapidapi.com/api-sports/api/api-football/');
    $responses['TheRunDown'] = $client->get('https://rapidapi.com/therundown/api/therundown/');
    $responses['WebSearch'] = $client->get('https://rapidapi.com/contextualwebsearch/api/web-search/');
    $responses['GeoDBCities'] = $client->get('https://rapidapi.com/wirefreethought/api/geodb-cities/');
    $responses['TempMail'] = $client->get('https://rapidapi.com/Privatix/api/temp-mail/');
    $responses['Weather'] = $client->get('https://rapidapi.com/weatherbit/api/weather/');
    $responses['UnoGS'] = $client->get('https://rapidapi.com/unogs/api/unogs/');
    $responses['Recipe-Food'] = $client->get('https://rapidapi.com/spoonacular/api/recipe-food-nutrition/');
    $responses['WordsApi'] = $client->get('https://rapidapi.com/dpventures/api/wordsapi/');
   
    foreach ($responses as $key => $result)
    {
        echo $key . "<br>";
        //$result is a Guzzle Response object
        //do stuff with $result
        //eg. $result->getBody()
    }
    //print_r($responses);
    echo 'Sequential execution time in seconds: ' . (microtime(true) - $time_start);
}

public function concurrent()
{
    $time_start = microtime(true);
    $client = new Client();
    $promises = [
        'FlyFar' => $client->getAsync('<https://api.flyfarint.com/v.1.0.0/AirSearch/oneway.php?tripType=oneway&journeyfrom=DAC&journeyto=DXB&departuredate=2022-08-30&adult=1&child=0&infant=0>'),
        'DarkSky'   => $client->getAsync('<https://rapidapi.com/darkskyapis/api/dark-sky/>'),
        'API-NBA'  => $client->getAsync('<https://rapidapi.com/api-sports/api/api-nba/>'),
        'API-FOOTBALL'  => $client->getAsync('<https://rapidapi.com/api-sports/api/api-football/>'),
        'TheRunDown'  => $client->getAsync('<https://rapidapi.com/therundown/api/therundown/>'),
        'WebSearch'  => $client->getAsync('<https://rapidapi.com/contextualwebsearch/api/web-search/>'),
        'GeoDBCities'  => $client->getAsync('<https://rapidapi.com/wirefreethought/api/geodb-cities/>'),
        'TempMail'  => $client->getAsync('<https://rapidapi.com/Privatix/api/temp-mail/>'),
        'Weather'  => $client->getAsync('<https://rapidapi.com/weatherbit/api/weather/>'),
        'UnoGS'  => $client->getAsync('<https://rapidapi.com/unogs/api/unogs/>'),
        'Recipe-Food'  => $client->getAsync('<https://rapidapi.com/spoonacular/api/recipe-food-nutrition/>'),
        'WordsApi'  => $client->getAsync('<https://rapidapi.com/dpventures/api/wordsapi/>'),
    ];
    $responses = Utils::settle($promises)->wait();
    foreach ($responses as $key => $response)
    {
        echo $key ."<br>";
        //response state is either 'fulfilled' or 'rejected'
        if($response['state'] === 'rejected')
        {
            //handle rejected
            continue;
        }
        //$result is a Guzzle Response object
        $result = $response['value'];
        //do stuff with $result
        // $result->getBody();
    }
    //print_r($responses);
    echo 'Concurrent execution time in seconds: ' . (microtime(true) - $time_start);
}
}

