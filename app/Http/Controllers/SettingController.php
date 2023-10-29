<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Message;
use App\Traits\UploadAble;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use App\Http\Controllers\Controller;
use App\Models\Site\Site;
use App\Models\Transaction;
use App\Models\Meter\Meter;
use App\Http\Resources\TransactionResource;
use App\Http\Resources\MessageResource;

/**
 * Class SettingController
 * @package App\Http\Controllers\Admin
 */
class SettingController extends Controller
{
    use UploadAble;

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.settings.index');
    }

    /**
     * Get saved settings
     */
    public function getSettings() {
        $settings = \config('settings');
        return response()->json(['status' => true, 'message' => 'success', 'data' => $settings], 200);
    }

    /** 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        if($request->has('site_logo') && ($request->file('site_logo') instanceof UploadedFile)) {

            if (config('settings.site_logo') != null) {
                $this->deleteOne(config('settings.site_logo'));
            }
            $logo = $this->uploadOne($request->file('site_logo'), 'img');
            Setting::set('site_logo', $logo);

        } elseif($request->has('site_favicon') && ($request->file('site_favicon') instanceof UploadedFile)) {

            if (config('settings.site_favicon') != null) {
                $this->deleteOne(config('settings.site_favicon'));
            }
            $favicon = $this->uploadOne($request->file('site_favicon'), 'img');
            Setting::set('site_favicon', $favicon);
 
        } else {

            $keys = $request->except('_token');

            foreach ($keys as $key => $value)
            {
                Setting::set($key, $value);
            }
        }
        return response()->json(['status' => true, 'message' => 'Settings updated successfully.', 'data' => config('settings')], 201);
    }

    /**
     * Get Dashboard analytics data
     */
    public function dashboardAnalytics() {
        $sites = Site::get();
        $meters = Meter::get();
        $revenue = Transaction::where('type', 'credit')->sum('amount');

        $data = [
            'sites' => $sites,
            'meters' => $meters,
            'revenue' => number_format($revenue, 2),
            'kwh' => 121
        ];
        return response()->json(['status' => true, 'data' => $data]);
    }

    /**
     * Get traansactions
     */
    public function transactions() {
        $data = Transaction::orderBy('created_at', 'DESC')->get();
        return response()->json(['status' => true, 'data' => TransactionResource::collection($data)], 200);
    }

    /**
     * Get messages
     */
    public function getMessages() {
        $messages = Message::orderBy('created_at', 'DESC')->get();

        return response()->json(['status' => true, 'data' => MessageResource::collection($messages)], 200);
    }
}
 