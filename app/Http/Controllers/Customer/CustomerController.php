<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Services\CustomerService;
use App\Http\Resources\CustomerResource;

class CustomerController extends Controller
{
    protected $customerserivce;

    public function __construct(CustomerService $customerserivce) {
        $this->customerserivce = $customerserivce;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->customerserivce->getCustomers();
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
            'site_id' => 'required|string',
            'meter_id' => 'required|string',
        ]);
        
        if($validate->fails()) {
            return response()->json(['status' => false, 'message' => \implode(" ", $validate->messages()->all())]);
        }

        return $this->customerserivce->store($request);


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = $this->customerserivce->findCustomer($id);

        if($result) {
            return response()->json(['status' => true, 'message' => 'success', 'data' => new CustomerResource($result)], 200);
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
        $customer = $this->customerserivce->findCustomer($id);
        if($customer) {
            $update = $this->customerserivce->updateCustomer($request->all());

            if($update) {
                return response()->json(['status' => true, 'message' => 'succuess', 'data' => new CustomerResource($customer)]);
            }
            return response()->json(['status' => false, 'message' => 'Could not update details'], 422);
        }
        return response()->json(['status' => false, 'message' => 'Invalid customer ID or Customer not found'], 422);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = $this->customerserivce->deleteCustomer($id);
        if($delete) {
            return response()->json(['status' => true, 'message' => 'Record deleted']);
        }
        return response()->json(['status' => false, 'message' => 'Could not delete record'], 422);
 
    }
    
}
