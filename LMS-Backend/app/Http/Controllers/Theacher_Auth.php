<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Theacers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Theacher_Auth extends Controller
{
    public function Register(Request $request){
        $fields = $request->validate([
            'name' => 'required|string|max:20',
            'NIP' => 'required|string|unique:theacers',
            'phone' => 'required|string',
            'email' => 'required|string|unique:users',
            'address' => 'required|string',
            'username' => 'required|string',
            'password' => 'required|string|confirmed'

        ]);
        User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'username' => $fields['username'],
            'password' => bcrypt($fields['password']),
            'role' => "guru"
        ]);
        $id = DB::table('users')->orderBy('id', 'DESC')->limit(1)->get();
        foreach($id as $i){
            $id_user = $i->id; 
        }
        $Theacher = Theacers::create([
            'user_id' => $id_user,
            'NIP' => $fields['NIP'],
            'phone' => $fields['phone'],
            'address' => $fields['address'],
           
        ]);

        $token = $Theacher->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $Theacher,
            'token' => $token
        ];

        return response($response, 201);
    }
    
}
