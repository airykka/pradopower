<?php

namespace App\Services;

use App\Models\User;

class AdminService
{
  protected $admin;

  public function __construct(User $admin) {
      $this->admin = $admin;
  } 

  public function index() {
    return $this->admin->orderBy('first_name', 'ASC')->get();
  }

  public function store($request) {
    return $this->admin->create([
      'name' => $request->first_name.' '.$request->last_name,
      'first_name' => $request->first_name,
      'last_name' => $request->last_name,
      'email' => $request->email,
      'password' => \Hash::make($request->password),
      'username' => $request->username,
      'user_role' => $request->user_role
    ]);
  }

  public function findUser($id) {
    return $this->admin->find($id);
  }

  public function updateUser($request, $id) {
    $user = $this->admin->find($id);

    if($user) {
      $user->update($request->all());
      return response()->json(['status' => true, 'message' => 'success', 'data' => new UserResource($user)],200);
    }

    return response()->json(['status' => false, 'message' => 'failed'],422);
  }

  public function deleteUser($id) {
    $user = $this->admin->find($id);

    if($user) {
      return response()->json(['status' => true, 'message' => 'success', 'data' => new UserResource($user)],200);
    }

    return response()->json(['status' => false, 'message' => 'not found'], 404);    
  }
}
