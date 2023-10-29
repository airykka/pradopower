<?php

namespace App\Traits;

use Http;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\Transaction;
use App\Message;

/**
 * SMS trait
 */
trait SMS
{
  public function bulkSMSNg($to, $body, $id, $message_group) {
    $response = Http::post('https://www.bulksmsnigeria.com/api/v1/sms/create', [
      'api_token' => config('settings.sms_apitoken'),
      'from' => config('settings.app_name'),
      'to' => $to,
      'body' => $body,
      'dnd' => 3
    ]);
    
    $res = json_decode($response, true);
    
  //   \Log::info((array) $response->body());
    
    if((isset($response["data"]) && $response["data"]["status"] === "success") ) {
        $phoneNumber = "+234".$to;
        $this->twilioSms($body, $phoneNumber);
        Message::create([
          'message' => $body,
          'owner_id' => $id,
          'status' => true,
          'status_note' => 'Sent',
          'type' => $message_group,
        ]);

        return true;
    }

    return false;
    
  }
  
  public function twilioSms($msg, $phoneNumber, $id, $message_group) {
    $client = new Client(config("settings.twilio_sid"), config("settings.twilio_token"));
    
   $that = $client->messages->create(
    $phoneNumber,
    [
        "from" => config("settings.twilio_from"),
        "body" => $msg
    ]);

    Message::create([
      'message' => $msg,
      'owner_id' => $id,
      'status' => true,
      'status_note' => 'Sent',
      'type' => $message_group
    ]);
  }
}
