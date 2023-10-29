<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    protected $admin;

    public function __construct(User $admin) {
        $this->admin = $admin;
    } 

    public function login(Request $request) {
        $credentials = $request->only('email', 'password');

        if(auth()->attempt($credentials)) {
            return \response()->json(['status' => true, 'message' => 'success', 'data' => new UserResource(auth()->user())], 200);
        }

        return response()->json(['status' => false, 'message' => 'Invalid email or password'], 422);
    }

    public function logout(Request $request) {
        return auth()->user()->logout();
    }
}
