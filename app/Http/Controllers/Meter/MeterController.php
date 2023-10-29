<?php

namespace App\Http\Controllers\Meter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\MeterService;

class MeterController extends Controller
{

    protected $meterService; 

    public function __construct(MeterService $meterService) {
        $this->meterService = $meterService;
    }

    public function index() {
        return $this->meterService->index();
    }

    public function assignCustomer(Request $request) {
        return $this->meterService->assignToCustomer($request);
    }

    public function unasigned() {
        return $this->meterService->unasigned(); 
    }

    public function get_list() {
        $payload = [
            "CompanyName" => config('settings.calin_company_name'),
            "UserName" => config('settings.calin_user_name'),
            "Password" => config('settings.calin_password'),
            "StartIndex" => 1,
            "Count" => 100
        ];
        return $this->meterService->get_list('/COMM_CustomerMeterList', $payload);
    }

    public function remoteControl(Request $request) {
        $url =  '/COMM_RemoteControl';
        $payload = [
            "CompanyName" => config('settings.calin_company'),
            "UserName" => config('settings.calin_user_name'),
            "Password" => config('settings.calin_password'),
            "MeterNo" => $request->meter_number,
            "DataItem" => $request->option
        ];
        return $this->meterService->remoteControl($url, $payload);
    }

    public function remoteReading(Request $request) {
        $url =  '/COMM_RemoteReading';
        $payload = [
            "CompanyName" => config('settings.calin_company'),
            "UserName" => config('settings.calin_user_name'),
            "Password" => config('settings.calin_password'),
            "MeterNo" => $request->meter_number,
            "DataItem" => $request->option
        ];
        return $this->meterService->remoteControl($url, $payload);
    }

    public function remoteToken(Request $request) {
        $url =  '/COMM_RemoteControl';
        $payload = [
            "CompanyName" => config('settings.calin_company'),
            "UserName" => config('settings.calin_user_name'),
            "Password" => config('settings.calin_password'),
            "MeterNo" => $request->meter_number,
            "Token" => $request->option
        ];
        return $this->meterService->remoteControl($url, $payload);
    }

    public function purchase(Request $request) {
        $validator = \Validator::make($request->all(), [
            'units' => 'required|numeric|between:0,999999.99',
            'meter_number' => 'required|string'
        ],
        [
            'units.required' => 'Enter units',
            'units.numeric' => 'Unit not valid'
        ]);
        if($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }
        $url = '/POS_Purchase';
        $payload = [
            'company_name' => 'ARD',
            'user_name' => 'POS1',
            'password' =>  config('settings.calin_password'),
            'password_vend' => config('settings.calin_password_vend'),
            'meter_number' => $request->meter_number,
            'is_vend_by_unit' => true,
            'amount' => $request->units
        ];
        // $payload = [
        //     'company_name' => 'ARD',
        //     'user_name' => 'POS1',
        //     'password' =>  '123456',//config('settings.calin_password'),
        //     'password_vend' => '123456', //config('settings.calin_password_vend'),
        //     'meter_number' => '47000000001', //$request->meter_number,
        //     'is_vend_by_unit' => true,
        //     'amount' => $request->units
        // ];
        return $this->meterService->purchase($url, $payload, $request);
    }

    public function purchaseHistory(Request $request) {
        $url = '/POS_PurchaseHistory';
        $payload = [
            "company_name" => "ARD",
            "user_name" => "POS1",
            "password" => config('settings.calin_password'),
            "customer_number" => $request->customer_number,
            "customer_name" => $request->customer_name,
            "meter_number" => $request->meter_number,
        ];
        return $this->meterService->purchaseHistory($url, $payload);
    }

    public function clearCredit(Request $request) {
        $url = '/Maintenance_ClearCredit';
        $payload = [
            "company_name" => config('settings.calin_company'),
            "user_name" => config('settings.calin_user_name'),
            "password" => config('settings.calin_password'),
            "meter_number" => $request->meter_number,
        ];
        return $this->meterService->clearCredit($url, $payload);
    }

    public function clearTamper(Request $request) {
        $url = '/Maintenance_ClearTamper';
        $payload = [
            "company_name" => config('settings.calin_company'),
            "user_name" => config('settings.calin_user_name'),
            "password" => config('settings.calin_password'),
            "meter_number" => $request->meter_number,
        ];
        return $this->meterService->clearTamper($url, $payload);
    }
    
    public function setMaxPower(Request $request) {
        $url = '/Maintenance_SetMaxPower';
        $payload = [
            "company_name" => config('settings.calin_company'),
            "user_name" => config('settings.calin_user_name'),
            "password" => config('settings.calin_password'),
            "meter_number" => $request->meter_number,
            "max_power" => $request->max_power,
        ];
        return $this->meterService->setMaxPower($url, $payload);
    }
        
    public function meterStatus(Request $request) {
        $url = '/COMM_OnlineStatus';
        $payload = [
            "CompanyName" => config('settings.calin_company'),
            "UserName" => config('settings.calin_user_name'),
            "Password" => config('settings.calin_password'),
        ];
        return $this->meterService->onlineStatus($url, $payload);
    }

    public function dailyData(Request $request) {
        $payload = [
            "CompanyName" => config('settings.calin_company'),
            "UserName" => config('settings.calin_user_name'),
            "Password" => config('settings.calin_password'),
            "QueryList" => [
                [
                    "MeterNo" => $request->meter_number,
                    "Year" => $request->year,
                    "Month" => $request->month,
                    "Day" => $request->day
                ]
            ]
        ];
        return $this->meterService->getData('/COMM_DailyData', $payload);
    }
    

    public function hourlyData(Request $request) {
        $payload = [
            "CompanyName" => config('settings.calin_company'),
            "UserName" => config('settings.calin_user_name'),
            "Password" => config('settings.calin_password'),
            "QueryList" => [
                [
                    "MeterNo" => $request->meter_number,
                    "Year" => $request->year,
                    "Month" => $request->month,
                    "Day" => $request->day
                ]
            ]
        ];
        return $this->meterService->getData('/COMM_HourlyData', $payload);
    }

    public function monthlyData(Request $request) {
        $payload = [
            "CompanyName" => config('settings.calin_company'),
            "UserName" => config('settings.calin_user_name'),
            "Password" => config('settings.calin_password'),
            "QueryList" => [
                [
                    "MeterNo" => $request->meter_number,
                    "Year" => $request->year,
                    "Month" => $request->month,
                ]
            ]
        ];
        return $this->meterService->getData('/COMM_MonthlyData', $payload);
    }

    public function customer(Request $request) {
        $payload = [
            "company_name" => config('settings.calin_company'),
            "user_name" => config('settings.calin_user_name'),
            "password" => config('settings.calin_password'),
            "customer_number" => $request->customer->number,
            "meter_number" => $request->meter_number,
            "customer_name" => $request->customer_name
        ];
        return $this->meterService->customer('/POS_Customer', $payload);
    }

    public function customerList() {
        $payload = [
            "CompanyName" => config('settings.calin_company'),
            "UserName" => config('settings.calin_user_name'),
            "Password" => config('settings.calin_password'),
            "StartIndex" => 1,
            "Count" => 200
        ];
        return $this->meterService->customerList('/COMM_CustomerMeterList', $payload);
    }
      

    
}
