<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'fname' => $this->first_name,
            'lname' => $this->last_name,
            'telephone' => $this->phone_number,
            'email' => $this->email,
            'balance' => \number_format($this->account_balance, 2),
            'site' => $this->site,
            'name' => $this->site,
            'credit' => 'False',
            'status' => 'On',
            'energy' => 8.7,
            'intID' => 'View',
            'meter_id' => $this->meter_id,
            'site_id' => $this->site_id,
            'transactions' => TransactionResource::collection($this->transactions),
            'wallet' => new WalletResource($this->wallet),
            'account_balance' => !empty($this->wallet->balance) ? $this->wallet->balance : 0.00,
            'purchases' => $this->purchases,
            'tokens' => $this->meter, 
            'credit_balance' => number_format($this->credit_balance, 2),         
            'credit_threshold' => number_format($this->credit_threshold, 2) ,         
            'is_credit_limit' => $this->is_credit_limit,
            'user_type' => $this->user_type,
            'language' => $this->language,
            'meter' => new MeterResource($this->meter),
            'created_at_formatted' => $this->created_at->toFormattedDateString(),
            'updated_at_formatted' => $this->updated_at->toFormattedDateString(),
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            
        ];
    }
}
