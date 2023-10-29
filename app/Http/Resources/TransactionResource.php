<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'type' => $this->type,
            'sub_type' => $this->sub_type,
            'amount' => $this->amount,
            'formatted_amount' => number_format($this->amount, 2),
            'beneficiary' => $this->beneficiary,
            'reference' => $this->ref,
            'status' => $this->status,
            'description' => $this->description,
            'formatted_created_at' => $this->created_at->toFormattedDateString(),
            'created_at' => $this->created_at,
            'customer' => $this->user->first_name.' '.$this->user->last_name,
            'site' => $this->user->site->name,
        ];
    }
}
