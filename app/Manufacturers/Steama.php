<?php

namespace App\Manufacturers;


//use App\Interfaces\ManufacturerInterface;
use App\Models\Meter\MeterToken;
use Exception;
use Illuminate\Support\Facades\Log;
use function config;

class Steama 
{

  public function getToken() {
    //$base_url = 'https://api.steama.co/get-token/';
    $response = \Http::withHeaders([
        'Content-Type' => 'application/json',
    ])->post(trim(config('settings.steama_url')).'get-token/', [
      'username' => trim(config('settings.steama_username')),
      'password' => trim(config('settings.steama_password'))
    ]);
    if(isset($response['token'])) {
      return $response['token'];
    }
  }

  public function make_request($url, $payload) {
    //$base_url = config('settings.steama_url');
    $response = \Http::withHeaders([
      'Content-Type' => 'application/json',
      'Authorization' => 'Token '.$this->getToken()
    ])->post(config('settings.steama_url').'/'.$url, $payload);
    return $response;
  }

    /**
     * @param $tokenData
     *
     * @return MeterToken
     * @throws Exception
     */
    public function generateToken($tokenData): MeterToken
    {
        $timestamp = time();
        $toCharge = round($tokenData->transaction->amount / (float)($tokenData->tariff->price / 100), 1);
        $cipherText = $this->generateCipherText($tokenData->meter->id, 'PRADOP',
            $tokenData->meter->serial_number, 'CreditToken', $toCharge, $timestamp);

        $tokenParams = [
            'serial_id' => $tokenData->meter->id,
            'user_id' => 'PRADOP',
            'meter_id' => $tokenData->meter->serial_number,
            'token_type' => 'CreditToken',
            'amount' => $toCharge,
            'timestamp' => $timestamp,
            'ciphertext' => $cipherText,
        ];

        //TODO make request in a seperate funciton
        $request = \Http::withHeaders([
            'Content-Type' => 'application/json;charset=utf-8',
            'Content-Length:' . strlen(json_encode($tokenParams)),            
        ])->post(
            "http://api.calinhost.com/api/token",
            [
                'body' => json_encode($tokenParams)
            ]
        );

        $tokenResult = $request->json();

        //token generation failed, re-try to re-create the token 2 more times
        if ((int)$tokenResult['result_code'] !== 0) {
            Log::critical('Token generation failed', $tokenParams);
            throw  new Exception($tokenResult['reason']);

        }
        $token = $tokenResult['result'];


        $tokenModel = new MeterToken();
        $tokenModel->token = $token;
        $tokenModel->energy = $toCharge;

        return $tokenModel;
    }

    private function generateCipherText($serialID, $userID, $meterID, $tokenType, $amount, $timestamp): string
    {
        return md5(
            sprintf('%s%s%s%s%s%s%s',
                $serialID, $userID, $meterID, $tokenType, $amount, $timestamp, config('settings.steama.key'))
        );
    }
    
}
