<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MainService;

class DataController extends Controller
{

    protected $dataService; 

    public function __construct(MainService $dataService) {
        $this->dataService = $dataService;
    }    

    public function dailyData() {
        $payload = [
            "CompanyName" => "ARD",
            "UserName" => "POS1",
            "Password" => "123456",
            "StartIndex" => 0,
            "Count" => 1000
        ];
        return $this->dataService->get_daily_data('COMM_DailyData', $payload);
    }
    

    public function hourlyData() {
        $payload = [
            "CompanyName" => "ARD",
            "UserName" => "POS1",
            "Password" => "123456",
            "QueryList" => [
                "MeterNo" => "47000000001",
                "Year" => 2021,
                "Month" => 5,
                "Day" => 20
            ]
        ];
        return $this->dataService->get_hourly_data('COMM_HourlyData', $payload);
    }

    public function monthlyData() {
        $payload = [
            "CompanyName" =>  "ARD",
            "UserName" =>  "POS1",
            "Password" =>  "123456",
            "QueryList" =>  [
                "MeterNo" =>  "47000000001",
                "Year" =>  2021,
                "Month" =>  05
            ]
        ];
        return $this->dataService->get_monthly_data('COMM_MonthlyData', $payload);
    }
    
}
