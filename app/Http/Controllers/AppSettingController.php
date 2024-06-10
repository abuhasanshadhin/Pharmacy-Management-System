<?php

namespace App\Http\Controllers;

use App\Models\AppOption;
use App\Models\DatabaseBackup;
use App\Models\Setting;
use Exception;
use Flasher\Laravel\Facade\Flasher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AppSettingController extends Controller
{
    /**
     * Get application settings
     *
     * @return \Illuminate\Http\Response
     */
    public function settings()
    {
        return view('settings.form');
    }

    /**
     * Update application settings
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateSettings(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->except('_token');
            $logo =  $request->file('logo');
            $favicon =  $request->file('favicon');
            if(!empty($logo)){
                $data['logo'] = $this->upload($logo, 'settings',\setting('logo'));
            }
            if(!empty($favicon)){
                $data['favicon'] = $this->upload($favicon, 'settings',\setting('favicon'));
            }
            foreach ($data as $name => $value) {
                Setting::updateOrCreate(
                    ['name' => $name],
                    ['value' => $value]
                );
                Cache::forget('setting_' . $name);
            }
            DB::commit();
            return redirect()->route('settings')->with('success', 'Save changes successfully');
        } catch (Exception $e) {
            return redirect()->route('settings')->with('error', $e->getMessage());
        }
    }


    public function changeLanguage($language)
    {
        if(!isset($language)){
           abort(404);
        }
        App::setLocale('bn');
        session()->put('lang', $language);
        return redirect()->back();
    }


    public function restartSystem()
    {
        Artisan::call('optimize:clear');
        Artisan::call('debugbar:clear');
        Artisan::call('storage:link');

        Flasher::addSuccess('System Cache Cleared.');
        return redirect()->back();
    }

}
