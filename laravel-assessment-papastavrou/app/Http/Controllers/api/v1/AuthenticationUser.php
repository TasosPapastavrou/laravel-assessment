<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthenticationUser extends Controller
{
    private $loginError = "incorrect credentials";
    private $registerError = "exists user";
    private $logoutError = "logout faild";
    private $logoutSuccess = "successful longout";

    public function login(Request $request){

        $login = $request->validate([
            'email' => 'required|email:rfc,dns',
            'password' => 'required'
        ]);

        if(!Auth::attempt($login)){
            return response()->json(['message'=>$this->loginError],401);
        }

        $accessToken = Auth::user()->createToken('authToken')->accessToken;
        $user = Auth::user();
        $userInfo = collect(['userName' => $user->name, 'email' => $user->email]);

        return response()->json(['userInfo'=>$userInfo,'token'=>$accessToken],200); 

    }


    public function register(Request $request){

        $login = $request->validate([
            'name' => 'required',
            'email' => 'required|email:rfc,dns',
            'password' => 'required'
        ]);

        $userExist = User::where('email','=',$request->email)->first();

        if($userExist){
            return response()->json(['message'=>$this->registerError],409);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $accessToken = $user->createToken('auth_token')->accessToken;
        $userInfo = collect(['userName' => $user->name, 'email' => $user->email]);
        
        return response()->json(['userInfo'=>$userInfo,'token'=>$accessToken],201); 

    }


    public function logout(){   

        if(Auth::check()){
            Auth::user()->token()->revoke();
            return response()->json(['message'=>$this->logoutSuccess],200);
         }

         return response()->json(['message'=>$this->logoutError],401);
        
    }



}
