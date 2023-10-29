<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\PaymentService;


class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService) {
        $this->paymentService = $paymentService;
    }

    public function webhookConfirmation(Request $request) {
        return $this->paymentService->webhookConfirmation($request);
    }
}
