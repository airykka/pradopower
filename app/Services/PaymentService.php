<?php

namespace App\Services;

use App\Traits\Monnify;

class PaymentService
{
  use Monnify;

  public function webhookConfirmation($request) {
    return $this->webhook($request);
  }
}
