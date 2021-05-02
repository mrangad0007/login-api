<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Quote;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use Laravel\Passport\Client as OClient;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $credentials = request(['email', 'password']);

        if(!Auth::attempt($credentials)){
            return response()->json([
                'message' => 'Invalid email or password'
            ], 401);
        }

        $user = $request->user();

        $token = $user->createToken('Access Token');

        $user->access_token = $token->accessToken;

        return response()->json([
           "user"=>$user
        ], 200);

    }

    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed'
        ]);

        $user = new User([
             'name'=>$request->name,
             'email'=>$request->email,
             'password'=>bcrypt($request->password)   
        ]);

        $user->save();

        return response()->json([
            "message" => "User registered successfully"   
        ], 201);
    }


    public function quotesSignUp(Request $request)
    {
        $request->validate([
            'author' => 'required|string',
            'quote' => 'required|string'
        ]);

        $quote = new Quote([
             'author'=>$request->author,
             'quote'=>$request->quote  
        ]);

        $quote->save();

        return response()->json([
            "message" => "Quote registered successfully"   
        ], 201);
    }

    public function quotesFetch(Request $request)
    {
        $quote = Quote::all();

        return response()->json([
            "quotes"=>$quote
         ], 200);

    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            "message"=>"User logged out successfully"
        ], 200);
    }

    public function index()
    {
        echo "Hello World";
    }
}
