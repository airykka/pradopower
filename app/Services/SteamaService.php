<?php
namespace App\Services;

use App\Models\Meter\Meter;
use App\Manufacturers\Steama;
use App\Http\Resources\MeterResource;
use App\Models\Meter\MeterToken;
use App\Models\Meter\Purchase;
use App\Models\Wallet;
use App\Jobs\PurchaseJob;
use App\Traits\SMS; 

class SteamaService 
{

  use SMS;

  protected $meter;
  protected $api;
  protected $metertoken;
  protected $tokenpurchase;
  protected $wallet;

  public function __construct(Wallet $wallet, Meter $meter, Steama $api, MeterToken $metertoken, Purchase $tokenpurchase) {
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

  public function coreCount($url, $payload) {
    $cores = $this->api->make_request($url, $payload);
    return response()->json([
      'status' => true,
      'data' => $core,
    ],200);
  }

  public function meterCount($url, $payload) {
    $cores = $this->api->make_request($url, $payload);
    return response()->json([
      'status' => true,
      'data' => $core,
    ],200);
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
   * Fetch meter list from API\-09832RTYUIOP
   */
  public function get_list($url) {
    $response = \Http::withHeaders([
      'Content-Type' => 'application/json',
      'Authorization' => 'Token '.$this->api->getToken()
    ])->get(config('settings.steama_url').$url);
    if(isset($response['count'])) {
      return response()->json(['status' => true, 'data' => $response->json()], 200);
    }
    return response()->json(['status' => false, 'message' => $response['detail']], 422);
  }

}


