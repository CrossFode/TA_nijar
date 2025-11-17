<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
public function register(Request $request)
{
$request->validate([
'name' => 'required|string|max:255',
'email' => 'required|email|unique:users,email',
'password' => 'required|min:8'
]);


$user = User::create([
'name' => $request->name,
'email' => $request->email,
'password' => Hash::make($request->password)
]);


return response()->json(['status' => 'registered']);
}


public function login(Request $request)
{
$credentials = $request->validate([
'email' => 'required|email',
'password' => 'required'
]);


if (!Auth::attempt($credentials)) {
return response()->json(['error' => 'Invalid credentials'], 401);
}


$token = $request->user()->createToken('api')->plainTextToken;
return response()->json(['token' => $token]);
}
}