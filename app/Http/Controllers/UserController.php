<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BaseController as BaseController;
use Validator;
use App\Models\User;

class UserController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::select('id','username','email','role')->get()->toArray();
        if(isset($users)){
            return $this->sendResponse($users,"User fetched successfully."); 
        }else{
            return $this->sendError('User Role',['error'=>'User not available.']);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
                'username'=>'required',
                'role'=>'required',
                'password'=>'required',
                'email'=>'required|email|unique:users',
            ]);
        if($validator->fails()){
            return $this->sendError('Validator Error',$validator->errors());
        }
        $input = $request->all();
        $input["password"] = bcrypt($input["password"]);
        $newUser = User::create($input);
        if($newUser){
            $success['token'] = $newUser->createToken('AuthToken')->accessToken;
            $success['user'] =$newUser;
            if($success){
                return $this->sendResponse($success,'User created Successfully.');
            }else{
                return $this->sendError('Failed to create user.');
            }
        }else{
            return $this->sendError('Failed to create user.');
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
        $user =User::select('id','username','email','role')
        ->where('users.id',$id)
        ->get()->toArray();
        if (is_null($user)) {
            return $this->sendError('Not found.');
        }else{
            return $this->sendResponse($user,"Details retrieved successfully.");
        }   
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
        $input = $request->all();
        $validator = Validator::make($request->all(),[
            'username'=>'required',
            'role'=>'required',
            'email' => 'required|email|unique:users,email,'.$id,
        ]);
        if($validator->fails()){
            return $this->sendError('Validator Error',$validator->errors());
        }
        
        $userUpdate = User::where('id',$id)->update($input);

        if(isset($userUpdate)){
           return $this->sendResponse($userUpdate,"User updated successfully."); 
        }else{
            return $this->sendError('User not updated successfully.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $userDelete = User::where('id',$id)->delete();
        if($userDelete){
            return $this->sendResponse($userDelete,"user deleted successfully.");
        }else{
            return $this->sendError('Not deleted',['error'=>'user not deleted successfully.']);
        }
    }
}
