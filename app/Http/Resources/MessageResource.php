<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
            'customer' => $this->customer->first_name.' '.$this->customer->last_name,
            'agent' => $this->agent->first_name.' '.$this->agent->last_name,
            'meter' => $this->meter->site->name,
            'message' => $this->message,
            'status' => $this->status,
            'status_note' => $this->status_note,
            'type' => $ths->type
        ];
    }
}
