<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AdminService;
use App\Http\Resources\UserResource;

class AdminController extends Controller
{

    protected $admin;

    public function __construct(AdminService $admin) {
        $this->admin = $admin;
    } 

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = $this->admin->index();
        return response()->json(['status' => true, 'message' => 'success', 'data' => UserResource::collection($users)],200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'first_name' => 'string|required',
            'last_name' => 'string|required',
            'email' => 'string|required',
            'username' => 'sometimes|string',
            'user_role' => 'sometimes|string',
            'password' => 'string|required|min:8|confirmed',
        ]);

        if($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'failed', 'errors' => $validator->errors()]);
        }

        $store = $this->admin->store($request);
        if($store) {
            return \response()->json(['status' => true, 'message' => 'success', 'data' => new UserResource($store)],201);
        }

        return response()->json(['status' => false, 'message' => 'Could not create user',], 422);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = $this->admin->findUser($id);

        if($user) {
            return response()->json(['status' => true, 'message' => 'success', 'data' => new UserResource($user)], 200);
        }

        return response()->json(['status' => false, 'message' => 'Not found', 'data' => null], 404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        return $this->admin->updateUser($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->admin->deleteUser($id);
    }
}
