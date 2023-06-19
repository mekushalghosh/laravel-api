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
}
