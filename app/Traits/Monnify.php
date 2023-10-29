<?php

namespace App\Traits;

use Http;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\Transaction;
use App\Traits\SMS;

/**
 * Trait Monnify
 * @package App\Traits
 */
trait Monnify
{

  /**
   * Generate authentication token
   */
  public function getAccessToken() {
    $url = config('settings.monnify_url').'v1/auth/login';
   
    try {
      $response = Http::withHeaders([
        'Authorization' =>  'Basic '.base64_encode(trim(config('settings.monnify_apiKey')).':'.trim(config('settings.monnify_secrete_key'))),
        'content-type' =>  'application/json',
        'cache-control' => 'no-cache',
      ])->post($url);
      \Log::info($response);
      if($response['requestSuccessful'] === true && isset($response['responseMessage']) && $response['responseMessage'] === 'success' && isset($response['responseBody'])) {
        return $response['responseBody']['accessToken'];
      }

    } catch (\Throwable $th) {
      \Log::info($th);
      throw new HttpResponseException (
        response()->json(['status' => false, 'errors' => $th, 'message' => $th], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
      );
    }
  }


  /** 
   * Create virtual account
   */
  public function reserveAccount(array $wallet) {
    $url = config('settings.monnify_url').'v1/bank-transfer/reserved-accounts';
    
    try {

      $response = Http::withHeaders([
        'Authorization' => 'Bearer '.$this->getAccessToken(),
        'Content-Type' => 'application/json',
      ])->post($url, [
        'accountReference' => $this->refCode(),
        'accountName' => $wallet['first_name'].' '.$wallet['last_name'],
        'currencyCode' => 'NGN',
        'contractCode' => trim(config('settings.monnify_contract_code')),
        'customerEmail' => $wallet['email'],
      ]);

      \Log::info($response);
      
      if(isset($response['responseMessage']) && strtolower($response['responseMessage']) === 'success' && isset($response['responseBody'])) {
        return $response['responseBody'];
      }

    } catch (\Throwable $th) {
      \Log::info($th);
      throw new HttpResponseException (
        response()->json(['status' => false, 'errors' => $th, 'message' => $th], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
      );
    }
  }

  /** 
   * Transfer funds from virtual account to bank account
   */
  public function bankTransfer($wallet) {
    $url = config('settings.monnify_url').'v2/disbursements/single';
    
    try {

      $response = Http::withHeaders([
        'Authorization' => 'Basic '.base64_encode(config('settings.monnify.monnify_apiKey').':'.config('settings.monnify.secrete_key')),
        'Content-Type' => 'application/json',
      ])->post($url, [
        'amount' => $request->amount,
        'reference' => $this->refCode(),
        'narration' => $request->note,
        'destinationBankCode' => $request->bankCode,
        'destinationAccountNumber' => $request->accountNumber,
        'currency' =>  'NGN',
        'sourceAccountNumber' => config('settings.monnify_walletID')
      ])->json();

      if($response['requestSuccessful'] === true && isset($response['responseMessage']) && $response['responseMessage'] === 'success' ) {
        return $response->responseBody;
      }

    } catch (\Throwable $th) {
      throw new HttpResponseException (
        response()->json(['status' => false, 'errors' => $th, 'message' => $th], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
      );
    }
  }

  /**
   * Get list of banks
   */
  public function getBanks() {
    $url = config('settings.monnify_url').'banks';

    try {

      $response = Htp::withHeaders([
        'Authorization' => 'Bearer ',
        'content-type' => 'application/json',
        'cache-control' => 'no-cache',
      ])->get()->json();

      return $response;

    } catch (\Throwable $th) {
      throw new HttpResponseException (
        response()->json(['status' => false, 'errors' => $th, 'message' => $th], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
      );
    }
  }

  /**
   * Webhook
   */
  public function webhook($request) {

    $transaction_keys = config('settings.monnify_secrete_key').'|'.$request['paymentReference'].'|'.$request['amountPaid'].'|'.$request['paidOn'].'|'.$request['transactionReference'];
    $transaction_hash = hash('SHA512', $transaction_keys);

    if($transaction_hash === $request['transactionHash']) {

        $wallet = Wallet::where('account_ref', '=', $request['product']['reference'])->first();

        $user = !empty($wallet->customer) ?  Customer::where('id', $wallet->user_id)->first() : Agent::where('id', $wallet->user_id)->first();   

        $wallet->update([
            'amount' => $request['amountPaid'],
            'balance' => $request['amountPaid'] + $wallet->balance,
            'prev_balance' => $wallet->balance
        ]);

        $msg = $user->first_name.' '.$user->last_name.' wallet topup of '.$request['amountPaid'].' is successful';

        Transaction::create([
          'beneficiary' => $user->first_name.' '.$user->last_name,
          'reference' => $request['transactionReference'],
          'amount' => $request['amountPaid'],
          'user_id' => $user->id,
          'status' => 'success',
          'type' => 'credit',
          'sub_type' => 'Wallet Topup',
          'description' => config('settings.app_name').' wallet topup'
        ]);

        $this->SMS->bulkSMSNg($user->phone_number, $msg, $user->id, $user->type) ? null : $this->SMS->twilioSms($msg, $user->phone_number, $user->id, $user->type);

        \http_response_code(200);
        exit();
    }
  }

  public function refCode() {
    return bin2hex(\random_bytes(6));
  }
  
}


