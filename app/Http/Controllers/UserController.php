<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class UserController extends Controller
{
    public function getUsers(){
        $users = User::all();
        return $users;
    }

    //Checks that if email exists or not
    public function checkEmailExists(string $email){
        $emailExists = User::where('email', $email)->exists();
        return $emailExists;
    }

    public function createUser(Request $data){

        //Edge case if anyfield is empty
        if(!$data-> filled('password') || !$data-> filled('name') || !$data-> filled('email') ){
            return response()-> json(['message' => 'Some fields are empty, please check again'],200);
        }

        //If email exists then it will send response back
        if($this->checkEmailExists($data['email'])){
            return response()-> json(['message' => 'Email already taken'],200);
        }

        $validatedData = $data -> validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);

        
        $user = User::create($validatedData);
        return response()-> json(['message' => 'Successfully created user', 'user' => $user],201);
    }

    public function updateUser(Request $data){
        //Edge case for empty email field
        if(!$data-> filled('email') || !$data-> filled('name') ){
            return response()-> json(['message' => 'Fields are empty please check again'],200);
        }

        //Return response if there is no user exists
        if(!$this->checkEmailExists($data['email'])){
            return response()-> json(['message' => 'User doesnot exists'],200);
        }

        //Code for if user exists
        $user = User::where('email',$data['email'])->update(['name'=>$data['name']]);
        return response()-> json(['message' => 'User updated successfully'],201);
    }

    public function deleteUser(Request $data){
        //Edge case for checking empty email field
        if(!$data->filled('email')){
            return response()->json(['message'=>'Some fields are empty, please check again.'],400);
        }

        //Return response if there is no user exists
        if(!$this->checkEmailExists($data['email'])){
            return response()-> json(['message' => 'User doesnot exists'],200);
        }

        //Code for deleting users
        $user = User::where('email',$data['email'])->delete();
        return response()-> json(['message' => 'User deleted successfully'],201);
    }

    public function loginUser(Request $data){
        Auth::attempt(['email' => $data['email'], 'password' => $data['password']]);
        $user = Auth::user();
        // dd($user);
        $token = $user->createToken('mytoken')->plainTextToken;
        return response()->json(['token' => $token],200);

    }

    public function userDetails(){
        $user = Auth::user();
        // dd($user);
        return response()->json(['data' => $user],200);
    }
}


