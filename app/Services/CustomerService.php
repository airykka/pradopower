<?php
  namespace App\Services;

use App\Models\Customer;
use App\Http\Resources\CustomerResource;
use App\Models\Meter\Meter;
use App\Models\Meter\MeterSite;
use App\Models\Wallet;
use App\Traits\Monnify;


class CustomerService {

  use Monnify;

  protected $customer;
  protected $meter;
  protected $metersite;

  public function __construct(Customer $customer, Meter $meter, MeterSite $metersite) {
    $this->customer = $customer;
    $this->meter = $meter;
    $this->metersite = $metersite;
  }

  public function getCustomers() {
    $data = $this->customer->where('status', 1)->get();
    return \response()->json(['status' => true, 'data' => CustomerResource::collection($data)], 200);
  }

  public function store($request) {

    $wallet = $this->reserveAccount($request->all());

    $save = $this->customer->create([
      'first_name' => $request->first_name,
      'last_name' => $request->last_name,
      'phone_number' => $request->phone_number,
      'site' => $request->site,
      'language' => $request->language,
      'account_balance' => $request->energy_price,
      'meter_id' => $request->meter_id,
      'site_id' => $request->site_id,
      'email' => $request->email,
      'password' => \Hash::make($request->phone_number),
      'user_type' => 'customer'
    ]);

    if($save) {
      $this->meter->find($request->meter_id)->update(['status' => 1]);
      $this->metersite->create(['site_id' => $request->site_id, 'meter_id' => $request->meter_id]);
      Wallet::create([
        'user_id' => $save->id,
        'balance' => 0.00,
        'amount' => 0.00,
        'prev_balance' => 0.00,
        'account_ref' => $wallet['accountReference'],
        'account_number' => $wallet['accountNumber'],
        'currency' => $wallet['currencyCode'],
        'bank_name' => $wallet['bankName'],
        'bank_code' => $wallet['bankCode'],
        'status' => true
      ]);      
      return response()->json(['status' => true, 'message' => 'Customer Created', 'data' => new CustomerResource($save)], 201);
    }

    return \response()->json(['status' => false, 'message' => 'Could not create customer details'], 422);
  }

  public function findCustomer($id) {
    return $this->customer->find($id);
  }

  public function updateCustomer(array $request) {
    return $this->customer->update($request);
  }

  public function deleteCustomer($id) {
    $customer = $this->customer->find($id);

    if($customer) {
      $customer->wallet->delete();
      $customer->sites()->delete();
      $customer->meters()->delete();
      $customer->delete();

      return true;
    }

    return false;
  }  

}