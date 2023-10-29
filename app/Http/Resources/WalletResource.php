<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WalletResource extends JsonResource
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
            'user_id' => $this->user_id,
            'balance' => number_format($this->balance,2),
            'amount' => number_format($this->amount, 2),
            'account_number' => $this->account_number,
            'account_ref' => $this->account_ref,
            'prev_balance' => number_format($this->prev_balance,2),
            'currency' => $this->currency,
            'bank_name' => $this->bank_name,
            'bank_code' => $this->bank_code,
            'status' => $this->status,
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            'created_at_formatted' => $this->created_at->toFormattedDateString(),
            'updated_at_formatted' => $this->updated_at->toFormattedDateString(),

        ];
    }
}
