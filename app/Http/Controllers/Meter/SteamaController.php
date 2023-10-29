<?php

namespace App\Http\Controllers\Meter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SteamaService;

class SteamaController extends Controller
{

    protected $meterService; 

    public function __construct(SteamaService $meterService) {
        $this->meterService = $meterService;
    }

    public function index() {
        return $this->meterService->index();
    }

    public function coreCount() {
        return $this->meterService->coreCount(
            '/sites/9/active-core-counts/?start_time=2015-01-01T00:00:00&end_time=2015-01-07'
        );
    }

    public function meterCount() {
        return $this->meterService->coreCount(
            '/sites/9/active-meters/?start_time=2015-01-01T00:00:00&end_time=2015-01-07'
        );
    }

    public function unasigned() {
        return $this->meterService->unasigned();
    }

    public function get_list() {
       // $param = request()->query();
        // switch (request()->query()) {
        //     case request()->query('is_active'):
        //         $param = 'is_active=true';
        //         break;
            
        //     default:
        //         $param = '?is_active=false';
        //         break;
        // }
        //\Log::info($param);
        return $this->meterService->get_list('meters/');
    }  
}
