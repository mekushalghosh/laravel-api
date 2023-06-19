<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function getUsers(){
        $users = User::all();
        return $users;
    }

    public function createUser(Request $data){

        //Edge case if anyfield is empty
        if(!$data-> filled('password') || !$data-> filled('name') || !$data-> filled('email') ){
            return response()-> json(['message' => 'Some fields are empty, please check again'],200);
        }

        //Edge case for checking if email already exists or not
        function checkUniqueEmail(string $email){
            $emailExists = User::where('email', $email)->exists();
            return $emailExists;
        }
        
        //If email exists then it will send response back
        if(checkUniqueEmail($data['email'])){
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

        //Edge case for if user doesnot exists
        function checkUserExists(string $email){
            return User::where('email',$email)-> exists();
        }

        //Return response if there is no user exists
        if(!checkUserExists($data['email'])){
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
        //Edge case for checking if user exists or no
        function checkUserExists(string $email){
            return User::where('email',$email)->exists();
        }
        //Return response if there is no user exists
        if(!checkUserExists($data['email'])){
            return response()-> json(['message' => 'User doesnot exists'],200);
        }

        //Code for deleting users
        $user = User::where('email',$data['email'])->delete();
        return response()-> json(['message' => 'User deleted successfully'],201);
    }
}


