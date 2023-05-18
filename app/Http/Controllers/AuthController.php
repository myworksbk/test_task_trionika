<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Http\Response;

class AuthController extends Controller
{
    private const AUTH_TOKEN = "Trionika";
    
    /**
     * The login method thtough api token
     *
     * @param Request $request
     * @return Response
     */
     public function login(Request $request)
     {
         if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) { 
             $user = Auth::user(); 
             $success['token'] =  $user->createToken(self::AUTH_TOKEN)->plainTextToken; 
             $success['name'] =  $user->name;
 
             return response()->json($success, 200);
         } else { 
             return response()->json(['error' => 'Unauthorized!'], 401);
         } 
 
     }
}
