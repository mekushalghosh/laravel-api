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
        $validatedData = $data -> validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);

        $user = User::create($validatedData);
        return response()-> json(['message' => 'Successfully created user', 'user' => $user],201);
    }
}
