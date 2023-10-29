<?php

namespace App\Manufacturers;


//use App\Interfaces\ManufacturerInterface;
use App\Models\Meter\MeterToken;
use Exception;
use Illuminate\Support\Facades\Log;
use function config;

class Calin 
{

    public function make_request($url, $payload) {

        $base_url = 'https://ami.calinhost.com/api';

        $response = \Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($base_url.'/'.$url, $payload);

        //$httpCode = $response->status();
        
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
                $serialID, $userID, $meterID, $tokenType, $amount, $timestamp, config('services.calin.key'))
        );
    }
    
}
