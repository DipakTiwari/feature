<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\User;

use Validator;
use Illuminate\Support\Facades\DB;

class ApiAuthController extends BaseController
{
    public function login(Request $request){
       
        if(Auth::attempt(['email'=>$request->email,'password'=>$request->password])){
            
            $user = Auth::User();
                   
            $success['token'] = $user->createToken('AuthToken')->accessToken;  
            $success['user'] =$user;
    
            return $this->sendResponse($success,'You Logged in Successfully!!');
        }else{
            return $this->sendError('UnAuthenticated',['error'=>'UnAuthorized...']);
        }
    }
    public function logout(Request $request){

        $token = $request->user()->token();
        $token->revoke();
        if(isset($token)){
            return $this->sendResponse("message",'You Have Successfully logout!!');
        }else{
            return $this->sendError('UnAuthenticated',['error'=>'UnAuthorized...']);
        }
    }
}
