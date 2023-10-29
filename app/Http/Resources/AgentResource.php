<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AgentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // \Log::info($this->wallet);
        return [
            'fname' => $this->first_name,
            'lname' => $this->last_name,
            'telephone' => $this->phone_number,
            'balance' => \number_format($this->credit_balance, 2),
            'site' => $this->site,
            'credit' => $this->is_credit_limited  === true ? 'True' : 'False',
            'wallet' => $this->wallet,
            'account_balance' => $this->wallet ? $this->wallet->balance : 0.00,  
            'email' => $this->email,
            'credit_balance' => number_format($this->credit_balance, 2),         
            'credit_threshold' => number_format($this->credit_threshold, 2) ,         
            'account_ref' => $this->account_ref,
            'status' => $this->status,
            'phone_number' => $this->phone_number,
            'language' => $this->language,
            'is_credit_limit' => $this->is_credit_limit,
            'created_at' => (string) $this->created_at->toFormattedDateString(),
            'updated_at' => (string) $this->updated_at->toFormattedDateString(),
        ];
    }
}
