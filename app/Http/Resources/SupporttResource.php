<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SupporttResource extends JsonResource
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
            'title' => $this->title,
            'message' => $this->message,
            'attachment' => asset('storage/'.$this->attchment),
            'comments' => $this->comments,
            'customer' => $this->customer,
            'agent' => $this->agent,
        ];
    }
}
