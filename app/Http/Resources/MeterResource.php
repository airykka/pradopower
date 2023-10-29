<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MeterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'meter_number' => $this->meter_number,
            'site_id' => $this->site_id ? $this->site_id : null,
            'status' => $this->status,
            'site' => $this->customer ? $this->customer->site : null, 
            'site_id' => $this->customer ? $this->customer->site_id : null,
            'count'  => 159,
            'utility' => 'Electricity',
            'customer_id' => $this->customer ? $this->customer->id : null,
            'status' => $this->status == 1 ? 'On' : 'Off',
            'active_unit' => 'Active',
            'uptime' => '100%',
            'tokens' => $this->tokens
        ];
    }
}
