<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Agent;
use App\Http\Resources\CustomerResource;


class AuthController extends Controller
{
    
    public function login(Request $request) {
       $u = Customer::where('email', 'peteritodo@gmail.com')->first();
        $u->update(['password' => \Hash::make('12345678')]);
        $credentials = array("email" => $request->email, "password" => $request->password);
        if(!$token = auth()->guard("customer")->attempt($credentials)) {
            return response()->json(["message" => "Invalid email or password", "status" => false], 422);
        }

        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token) {
        return response()->json([
          'status' => true,
          'access_token' => $token,
          'token_type' => 'bearer',
          'expires_in' => auth()->guard("customer")->factory()->getTTL() * 360,
          'user' => new CustomerResource(auth()->guard("customer")->user()),
        ]);
    }  

    public function changePassword($request) {

        $validator = \Validator::make($request->all(), [
            "old_password" => "required|min:6",
            "password" => "required|min:6|confirmed",
            "password_confirmation" => "required"
        ]);

        if($validator->fails()) {
            return response()->json(array("status" => false, "message" => $validator->errors()));
        }
        $user = auth()->guard("customer")->user();

        if(\Hash::check($request->old_password, $user->password)) {

            $resetPassword = $user->update([
                "password" => \Hash::make($request->password)
                ]);

            if($resetPassword) {
                return response()->json(array("status" => true, "message" => "Password reset successfull"));
            }
            else {
                return response()->json(array("status" => false, "message" => "Error reseting password"));
            }
        }

        return response()->json(array("status" => false, "message" => "Old password doesn't match"));

    }


    public function resetPassword($request) {

        $validator = \Validator::make($request->all(), [
            "password" => "required|min:6|confirmed",
            "password_confirmation" => "required"
        ]);

        if($validator->fails()) {
            return response()->json(array("status" => false, "message" => $validator->errors()));
        }
        $user = $this->profile->where('phone_number', $request->phone_number)->first(); //auth()->guard("profile")->user();

        $resetPassword = $user->update([
            "password" => \Hash::make($request->password)
        ]);
        if($resetPassword) {
            return response()->json(array("status" => true, "message" => "Password reset successfull", "user" => $user));
        }
        else {
            return response()->json(array("status" => false, "message" => "Error reseting password"));
        }
    }    
}
