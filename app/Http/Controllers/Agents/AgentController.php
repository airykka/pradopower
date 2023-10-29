<?php

namespace App\Http\Controllers\Agents;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AgentService;
use Validator;
use App\Http\Resources\AgentResource;

class AgentController extends Controller
{

    protected $agentservice;

    public function __construct(AgentService $agentservice) {
        $this->agentservice = $agentservice;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->agentservice->getAgents();
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
        $validate = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone_number' => 'required|string',
            'language' => 'required|string',
            'site' => 'required|string',
        ]);
        
        if($validate->fails()) {
            return response()->json(['status' => false, 'message' => \implode(" ", $validate->messages()->all())]);
        }

        return $this->agentservice->store($request);        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = $this->agentservice->findAgent($id);

        if($result) {
            return response()->json(['status' => true, 'message' => 'success', 'data' => new AgentResource($result)], 200);
        }
        return response()->json(['status' => false, 'message' => 'No data found'], 200);

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
        $agent = $this->agentservice->findAgent($id);
        if($agent) {
            $update = $this->agentservice->updateAgent($request->all());

            if($update) {
                return response()->json(['status' => true, 'message' => 'succuess', 'data' => new AgentResource($agent)]);
            }
            return response()->json(['status' => false, 'message' => 'Could not update details'], 422);
        }
        return response()->json(['status' => false, 'message' => 'Invalid agent ID or Agent not found'], 422);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = $this->agentservice->deleteAgent($id);
        if($delete) {
            return response()->json(['status' => true, 'message' => 'Record deleted']);
        }
        return response()->json(['status' => false, 'message' => 'Could not delete record'], 422);
 
    }

    /**
     * Topup customer wallet
     */
    public function customerTopup($sourceID, $destinationID, $amount) {
        return $this->agentservice->topupCustomer($sourceID, $destinationID, $amount);
    }
}
