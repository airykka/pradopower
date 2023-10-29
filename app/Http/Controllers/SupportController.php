<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SupportService;
use App\Http\Resources\SupportResource;

class SupportController extends Controller
{

    protected $supportService;

    public function __construct(SupportService $supportService) {
        $this->supportService = $supportService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tickets = $this->supportService->index();

        return \response()->json(['status' => true, 'message' => 'success', 'data' => SupportResource::collection($tickets)], 200);
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
        $validation = \Validator::make($request->all(), [
            'user_id' => 'required|string',
            'message' => 'required|string',
            'title' => 'required|string'
        ]);

        if($validation->fails()) {
            return response()->json(['status' => false, 'message' => 'Validation error', 'error' => $validation->errors()], 422);
        }

        $store = $this->supportService->store($request);

        if($store) {
            return response()->json(['status' => true, 'message' => 'success', 'data' => new SupportResource($store)], 201);
        }

        return response()->json(['status' => false, 'message' => 'Could not submit ticket'], 422);
    }

    public function comment(Request $request)
    {
        $validation = \Validator::make($request->all(), [
            'ticket_id' => 'required|string',
            'message' => 'required|string',
        ]);

        if($validation->fails()) {
            return response()->json(['status' => false, 'message' => 'Validation error', 'error' => $validation->errors()], 422);
        }

        $store = $this->supportService->createComment($request);

        if($store) {
            return response()->json(['status' => true, 'message' => 'success'], 201);
        }

        return response()->json(['status' => false, 'message' => 'Could not submit ticket'], 422);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ticket = $this->supportService->findTicket($id);

        if($ticket) {
            return response()->json(['status' => true, 'message' => 'success', 'data' => new SupportResource($ticket)], 200);
        }

        return response()->json(['status' => false, 'message' => 'Not found'], 404);

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
        $ticket = $this->supportService->updateTicket($request, $id);

        if($store) {
            return response()->json(['status' => true, 'message' => 'success'], 200);
        }

        return response()->json(['status' => false, 'message' => 'Could not update ticket'], 422);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ticket = $this->supportService->deleteTicket($id);

        if($ticket) {
            return response()->json(['status' => true, 'message' => 'success'], 200);
        }

        return response()->json(['status' => false, 'message' => 'Could not delete ticket'], 404);
    }
}
