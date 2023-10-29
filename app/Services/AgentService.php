<?php
  namespace App\Services;

use App\Models\Agent;
use App\Models\Customer;
use App\Http\Resources\AgentResource;
use App\Traits\Monnify;
use App\Traits\SMS;
use App\Models\Wallet;
use App\Transaction;

class AgentService {

  protected $agent;

  use Monnify;
  use SMS;

  public function __construct(Customer $agent) {
    $this->agent = $agent;
  }

  public function getAgents() {
    $data = $this->agent->where('status', 1)->get();
    return \response()->json(['status' => true, 'data' => AgentResource::collection($data)], 200);
  }

  public function findAgent($id) {
    return $this->agent->find($id);
  }

  public function store($request) {
    // \Log::info((array) config('settings'));
    $wallet = $this->reserveAccount($request->all());
    $save = $this->agent->create([
      'first_name' => $request->first_name,
      'last_name' => $request->last_name,
      'phone_number' => $request->phone_number,
      'site' => $request->site,
      'language' => $request->language,
      'credit_balance' => $request->threshold,
      'credit_threshold' => $request->threshold,
      'is_credit_limited' => $request->credit_status,
      'email' => $request->email,
      'password' => \Hash::make($request->phone_number),
      'user_type' => 'agent'
    ]);

    if($save) {
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
      return response()->json(['status' => true, 'message' => 'Agent details saved', 'data' => new AgentResource($save)]);
    }

    return \response()->json(['status' => false, 'message' => 'Could not create agent details']);
  }

  public function updateAgent(array $request) {
    return $this->agent->update($request);
  }

  public function deleteAgent($id) {
    $agent = $this->agent->find($id);

    if($agent) {
      $agent->wallet->delete();
      $agent->sites()->delete();
      $agent->meters()->delete();
      $agent->delete();

      return true;
    }

    return false;
  }


  public function topupCustomer($sourceWalletID, $destinationWalletID, $amount) {
    $sourceWallet = $this->wallet->find($sourceWalletID);
    $destinationWallet = $this->wallet->find($destinationWalletID);
    $user = $this->agent->find($sourceWallet->user_id);

    if($sourceWallet) {
      
      if($sourceWallet->balance <= $amount) {
        return response()->json(['status' => false, 'message' => 'Insufficient fund']);
      }

      if($amount < 500) {
        return response()->json(['status' => false, 'message' => 'Minimum transfer is 500']);
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

      Transaction::create([
        'beneficiary' => $user->first_name.' '.$user->last_name,
        'reference' => $this->refCode(),
        'amount' => $amount,
        'user_id' => $user->id,
        'status' => 'success',
        'type' => 'credit',
        'sub_type' => 'Wallet Topup',
        'description' => 'Customer wallet topup by agent'
      ]);
      
      $this->SMS->bulkSMSNg($user->phone_number, $msg, $user->id) ? null : $this->SMS->twilioSms($msg, $user->phone_number, $user->id);

      return response()->json(['status' => true, 'message' => 'Transfer successful']);
    }

    return response()->json(['status' => false, 'message' => 'Source wallet not found']);
  }

} 