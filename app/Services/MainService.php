<?php

/**
 * This class handles all requests made to the manufacturer API
 */

namespace App\Services;

use App\Manufacturers\Calin;
use App\Models\Wallet;

class MainService 
{

  protected $api;
  protected $wallet;

  public function __construct(Calin $api, Wallet $wallet) {
    $this->api = $api;
    $this->wallet = $wallet;
  }

  public function get_meter_list($url, $payload) {
    $meters = $this->api->make_request($url, $payload);
    return response()->json([
      'status' => true, 
      'data' => $meters, 
      'message' => 'success'],
    200);
  }

  public function get_daily_data($url, $payload) {
    $meters = $this->api->make_request($url, $payload);
    return response()->json([
      'status' => true, 
      'data' => $meters, 
      'message' => 'success'],
    200);
  }

  public function get_hourly_data($url, $payload) {
    $meters = $this->api->make_request($url, $payload);
    return response()->json([
      'status' => true, 
      'data' => $meters, 
      'message' => 'success'],
    200);
  }
  

  public function get_monthly_data($url, $payload) {
    $meters = $this->api->make_request($url, $payload);
    return response()->json([
      'status' => true, 
      'data' => $meters, 
      'message' => 'success'],
    200);
  }

  /**
   * InApp transfers
   */
  public function topupCustomer($sourceWalletRef, $destinationWalletRef, $amount) {
    $sourceWallet = $this->wallet->where('account_ref', $sourceWalletRef)->first();
    $destinationWallet = $this->wallet->where('account_ref', $destinationWalletRef)->first();
    
    if($sourceWallet) {

      if($sourceWallet->balance <= $amount) {
        return response()->json(['status' => false, 'message' => 'Insufficient fund']);
      }

      $destinationWallet->update([
        'amount' => $amount, 
        'balance' => $wallet->balance + $amount,
        'prev_balance' => $wallet->balance
      ]);
      $sourceWallet->update([
        'amount' => $amount, 
        'balance' => $wallet->balance - $amount,
        'prev_balance' => $wallet->balance
      ]);

      return response()->json(['status' => true, 'message' => 'Transfer successful']);
    }

    return response()->json(['status' => false, 'message' => 'Source wallet not found']);
  }
  
}
