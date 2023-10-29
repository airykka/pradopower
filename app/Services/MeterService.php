<?php
namespace App\Services;

use App\Models\Meter\Meter;
use App\Manufacturers\Calin;
use App\Http\Resources\MeterResource;
use App\Models\Meter\MeterToken;
use App\Models\Meter\Purchase;
use App\Models\Wallet;
use App\Jobs\PurchaseJob;
use App\Traits\SMS; 
use App\Models\Meter\CustomerMeter;

class MeterService 
{

  use SMS;

  protected $meter;
  protected $api;
  protected $metertoken;
  protected $tokenpurchase;
  protected $wallet;

  public function __construct(Wallet $wallet, Meter $meter, Calin $api, MeterToken $metertoken, Purchase $tokenpurchase) {
    $this->meter = $meter;
    $this->api = $api;
    $this->metertoken = $metertoken;
    $this->tokenpurchase = $tokenpurchase;
    $this->wallet = $wallet;
  }

  /**
   * Get all saved meters
   */
  public function index() {
    $meterList = $this->meter->get();
    return response()->json(['status' => true, 'data' => MeterResource::collection($meterList)], 200);
  }

  public function unasigned() {
    $meterList = $this->meter->where('status', 0)->get();
    return response()->json(['status' => true, 'data' => MeterResource::collection($meterList)], 200);
  }

  public function assignToCustomer($request) {
    $meter = $this->meter->whereMeterNumber($request->meter_number)->first();
    if($meter) {
      $check = CustomerMeter::whereMeterId($meter->id)->whereCustomerId($request->customer_id)->first();
      if($check) {
        return response()->json(['status' => false, 'message' => 'Meter already  assigned to this customer', 'data' => []], 422);
      }
      CustomerMeter::create([
        'customer_id' => $request->customer_id,
        'meter_id' => $meter->id,
      ]);
      $meter->update(['status' => false]);
      return response()->json(['status' => true, 'message' => 'Meter Assigned', 'data' => new MeterResource($meter)], 200);
    }
    return response()->json(['status' => false, 'message' => 'Meter not found', 'data' => [], ], 422);
  }

  public function unAssignCustomer($request) {
    $meter = $this->meter->whereMeterNumber($request->meter_number)->first();
    if($meter) {
      CustomerMeter::whereMeterId($meter->id)->whereCustomerId($request->customer_id)->delete();
      $meter->update(['status' => true]);
      return response()->json(['status' => true, 'Meter Assigned', 'data' => new MeterResource($meter)], 200);
    }
    return response()->json(['status' => false, 'message' => 'Meter not found', 'data' => [], ], 422);
  }
  

  /** 
   * Store new meters
   */
  public function store($meters) {
    $meterNumbers = $this->meter->get()->pluck('meter_number');
    foreach ($meters as $key => $meter) {
      if(!in_array($meter['MeterNumber'], $meterNumbers->toArray())) {
        $this->meter->create([
          'meter_number' => $meter['MeterNumber'],
          'site_id' => NULL,
          'status' => false
        ]);
      }
    }
  }

  /**
   * Get specific meter with the meter ID
   */
  public function show($meterId) {
    $meter = $this->meter->find($meterId);

    if(!empty($meter)) {
      return \response()->json(['status' => true, 'message' => 'success', 'data' => $meter], 200);
    }

    return \response()->json(['status' => false, 'message' => 'Meter not found'], 404);
  }

  /**
   * Delete saved meter
   */
  public function delete($meterId) {
    $meter = $this->meter->find($meterId);

    if(!empty($meter)) {
      return \response()->json(['status' => true, 'message' => 'success', 'data' => $meter]);
    }

    return \response()->json(['status' => false, 'message' => 'Meter not found']);
  }

  /**
   * Fetch meter list from API
   */
  public function get_list($url, $payload) {

    $result =  $this->api->make_request($url, $payload);
    
    if($result['ResultCode'] === '00' && $result['Reason'] === 'OK') {
      if(isset($result['Result']) && count($result['Result']) > 0) {
        $this->store($result['Result']);
        return response()->json(['status' => true, 'data' => $result['Result']]);
      }
      return response()->json(['status' => true, 'message' => 'No data returned', 'data' => []], 200);
    }
    return response()->json(['status' => false, 'message' => $result['Reason']], 422);
  }   
  
  /**
   * Generate task number for remote control task
   */
  public function remoteControl($url, $payload) {
    $response = $this->api->make_request($url, $payload);

    if($response['ResultCode'] === '00' && $response['Reason'] === 'OK') {
      if(isset($response['Result']) && count($response['Result']) > 0) {
        return $this->remoteTask($response['Result']['TaskNo'],'control');
      }
      return response()->json(['status' => true, 'message' => 'No data returned', 'data' => []], 200);
    }
    return response()->json(['status' => false, 'message' => $response['Reason']], 422);
  }

  /**
   * Remote Reading
   */
  public function remoteReading($url, $payload) {
    $response = $this->api->make_request($url, $payload);

    if($response['ResultCode'] === '00' && $response['Reason'] === 'OK') {
      if(isset($response['Result']) && count($response['Result']) > 0) {
        return $this->remoteTask($response['Result']['TaskNo'], 'reading');
      }
      return response()->json(['status' => true, 'message' => 'No data returned', 'data' => []], 200);
    }
    return response()->json(['status' => false, 'message' => $response['Reason']], 422);
  }

  /**
   * Remote Token
   */
  public function remoteToken($url, $payload) {
    $response = $this->api->make_request($url, $payload);

    if($response['ResultCode'] === '00' && $response['Reason'] === 'OK') {
      if(isset($response['Result']) && count($response['Result']) > 0) {
        return $this->remoteTask($response['Result']['TaskNo'], 'token');
      }
      return response()->json(['status' => true, 'message' => 'No data returned', 'data' => []], 200);
    }
    return response()->json(['status' => false, 'message' => $response['Reason']], 422);
  }
  
  /**
   * Perform actual remote control task with task number obtained
   */
  public function remoteTokenTask($taskNo, $type) {
    $payload = [
        "CompanyName" => "Pradopower",
        "UserName" => "AMR02",
        "Password" => "123456",
        "TaskNo" =>  $taskNo,
    ];
    switch ($type) {
      case 'control':
        $url = '/COMM_RemoteControlTask';
        break;
      case 'reading':
        $url = '/COMM_RemoteReadingTask';
        break;
      case 'token':
        $url = '/COMM_RemoteTokenTask';
        break;
      
      default:
        $url = '/COMM_RemoteControlTask';
        break;
    }

    $response = $this->api->make_request($url, $payload);
    
    if($response['ResultCode'] === '00' && $response['Reason'] === 'OK') {
      if(isset($response['Result']) && count($response['Result']) > 0) {
        $data = response()->json(['status' => true, 'data' => $response['Result']], 200); 

        if($type === 'token' && $data->status === true) {
          $this->metertoken->create([
            'meterNo' => $data->data->MeterNo,
            'taskNo' => $data->data->TaskNo,
            'token' => $data->data->Data,
            'CreateDate' => $data->data->CreateDate,
            'EndDate' => $data->data->EndDate,
            'tokenStatus' => $data->data->Status,
            'EndDate' => $data->data->EndDate,
          ]);
        }
        return $data;
      }
      return response()->json(['status' => true, 'message' => 'No data returned', 'data' => []], 200);
    }
    return response()->json(['status' => false, 'message' => $response['Reason']], 422);
  }

  /**
   * Token purchase
   */
  public function purchase($url, $payload, $request) { 
    $wallet = $this->wallet->where('user_id', $request->user_id)->first();
    $amount = trim(config('settings.unit_price')) * $request->units;

    if(!$wallet) {
      return response()->json(['status' => false, 'message' => 'The referenced account could not be found'], 422);
    }

    if($wallet->balance <= $amount) {
      return response()->json(['status' => false, 'message' => 'Insufficient fund'], 422);
    }

    $response = $this->api->make_request($url, $payload);

    if($response['result_code'] == 0 && $response['reason'] === 'OK') {
      if(isset($response['result']) && count($response['result']) > 0) {
        $details = [
          'status' => 'successful',
          'email' => $request->email,
          'last_name' => $request->last_name,
          'amount' => $response['result']['price'],
          'units' => $response['result']['total_unit'],
          'token' => $response['result']['token'],
          'reference' => $response['result']['TaskNo'],
          'currency' => $response['result']['currency'],
        ];
        $msg = 'Your purchase of '.$response['result']['total_unit'].'  was successful';
        $wallet->update(['amount' => $amount, 'balance' => $wallet->balance - $amount, 'prev_balance' => $wallet->balance]);
        \dispatch(new PurchaseJob($details));
        $this->tokenpurchase->create([
          'total_paid' =>  $response['result']['total_paid'],
          'total_unit' =>  $response['result']['total_unit'],
          'token' =>  $response['result']['token'],
          'customer_number' =>  $response['result']['customer_number'],
          'customer_name' =>  $response['result']['customer_name'],
          'customer_addr' =>  $response['result']['customer_addr'],
          'meter_number' =>  $response['result']['meter_number'],
          'gen_datetime' =>  $response['result']['gen_datetime'],
          'gen_user' =>  $response['result']['gen_user'],
          'company' =>  $response['result']['company'],
          'price' =>  $response['result']['price'],
          'vat' =>   $response['result']['vat'],
          'tid_datetime' =>  $response['result']['tid_datetime'],
          'currency' =>  $response['result']['currency'],
          'unit' =>  $response['result']['unit'],
          'TaskNo' =>  $response['result']['TaskNo'],
          'status' => false,
        ]);
        //$this->SMS->bulkSMSNg($request->phone_number, $msg, $request->user_id) ? NULL : $this->SMS->twilioSms($msg, $request->phone_number, $request->user_id);
        return \response()->json(['status' => true, 'message' => $msg, 'data' => $response['result']], 200);
      }
      return response()->json(['status' => true, 'message' => 'No data returned', 'data' => []], 200);
    }
    
    return response()->json(['status' => false, 'message' => $response['reason']], 422);

  }

  /**
   * Token purchase history
   */
  public function purchaseHistory($url, $payload, $request) {
    $purchases = $this->tokenpurchase->where('meter_number', $request->meter_number)->get();

    if($purchases) {
      return response()->json(['status' => true, 'message' => 'success', 'data' => $purchases]);
    }

    $response = $this->api->make_request($url, $payload);
    if($response['result_code'] === '0' && $response['reason'] === 'OK') {
      if(isset($response['result']) && count($response['result']) > 0) {
        return \response()->json(['status' => true, 'data' => $response['result']], 200);
      }
      return response()->json(['status' => true, 'message' => 'No data returned', 'data' => []], 200);
    }

    return response()->json(['status' => false, 'message' => $response['reason']], 422);

  }

  /**
   * Clear tamper
   */
  public function clearTamper($url, $payload) {

    $response = $this->api->make_request($url, $payload);
    if($response['result_code'] === '0' && strtoupper($response['reason']) === 'OK') {
      if(isset($response['result'])) {
        return \response()->json(['status' => true, 'message' => 'success', 'data' => $response['result']], 200);
      }
      return response()->json(['status' => true, 'message' => 'No data returned', 'data' => []], 200);
    }

    return response()->json(['status' => false, 'message' => $response['reason']], 422);

  }

  /**
   * Clear credit
   */
  public function clearCredit($url, $payload) {

    $response = $this->api->make_request($url, $payload);
    if($response['result_code'] === '0' && strtoupper($response['reason']) === 'OK') {
      if(isset($response['result'])) {
        return \response()->json(['status' => true, 'message' => 'success', 'data' => $response['result']], 200);
      }
      return response()->json(['status' => true, 'message' => 'No data returned', 'data' => []], 200);
    }

    return response()->json(['status' => false, 'message' => $response['reason']], 422);

  }

  /**
   * Set Maximum Power value
   */
  public function setMaxPower($url, $payload) {

    $response = $this->api->make_request($url, $payload);
    if($response['result_code'] === '0' && strtoupper($response['reason']) === 'OK') {
      if(isset($response['result'])) {
        return \response()->json(['status' => true, 'message' => 'success', 'data' => $response['result']], 200);
      }
      return response()->json(['status' => true, 'message' => 'No data returned', 'data' => []], 200);
    }

    return response()->json(['status' => false, 'message' => $response['reason']], 422);

  }

    /**
   * Online status
   */
  public function onlineStatus($url, $payload) {
    $response = $this->api->make_request($url, $payload);

    if($response['ResultCode'] === '00' && $response['Reason'] === 'OK') {
      if(isset($response['Result']) && count($response['Result']) > 0) {
        return response()->json(['status' => true, 'message' => 'success', 'data' => $response['Result']], 200);
      }
      return response()->json(['status' => true, 'message' => 'No data returned', 'data' => []], 200);
    }
    return response()->json(['status' => false, 'message' => $response['Reason']], 422);
  }

  /**
   * Get data
   */
  public function getData($url, $payload) {
    $response = $this->api->make_request($url, $payload);

    if($response['ResultCode'] === '00' && $response['Reason'] === 'OK') {
      if(isset($response['Result']) && count($response['Result']) > 0) {
        return response()->json(['status' => true, 'message' => 'success', 'data' => $response['Result']], 200);
      }
      return response()->json(['status' => true, 'message' => 'No data returned', 'data' => []], 200);
    }
    return response()->json(['status' => false, 'message' => $response['Reason']], 422);
  }

  /**
   * Customer
  */
  public function customer($url, $payload) {

    $response = $this->api->make_request($url, $payload);
    if($response['result_code'] === '0' && strtoupper($response['reason']) === 'OK') {
      if(isset($response['result'])) {
        return \response()->json(['status' => true, 'message' => 'success', 'data' => $response['result']], 200);
      }
      return response()->json(['status' => true, 'message' => 'No data returned', 'data' => []], 200);
    }

    return response()->json(['status' => false, 'message' => $response['reason']], 422);

  }

  /**
   * Get list of Customer
  */
  public function customerList($url, $payload) {

    $response = $this->api->make_request($url, $payload);
    if($response['ResultCode'] === '00' && strtoupper($response['Reason']) === 'OK') {
      if(isset($response['Result']) && count($response['Result']) > 0) {
        return \response()->json(['status' => true, 'message' => 'success', 'data' => $response['Result']], 200);
      }
      return response()->json(['status' => true, 'message' => 'No data returned', 'data' => []], 200);
    }

    return response()->json(['status' => false, 'message' => $response['Reason']], 422);

  }

}


