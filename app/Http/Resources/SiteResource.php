<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SiteResource extends JsonResource
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
            'name' => $this->name,
            'currency' => $this->currency,
            'revenue' => 0.00,
            'utility' => 0.00,
            'Meterno' => count($this->meters),
            'meters' => $this->meters,
            'phone_number' => $this->phone_number,
            'created_at' => (string) $this->created_at->toFormattedDateString(),
            'version' => '1.0',
            //''
        ];
    }
}
