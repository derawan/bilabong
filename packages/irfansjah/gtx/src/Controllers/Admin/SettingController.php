<?php

namespace Irfansjah\Gtx\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view("gtx::Admin.pages.settings.index");
    }

    public function store(Request $request)
    {
        $data = ($request->all());
        $sets =($data['sets']);
        foreach($sets as $group => $settings)
        {
            foreach($settings as $name => $value)
            {
                $setting = \Irfansjah\Gtx\Models\SystemConfig::where('group',$group)->where('name',$name)->first();
                if($setting)
                {
                    $setting->value = $value;
                    $setting->save();
                }
                else {
                    \Irfansjah\Gtx\Models\SystemConfig::create([
                        'name'=>$name, 'group'=>$group, 'value'=>$value
                    ]);
                }
            }
        }
        $message = sprintf(__("%s has just modifying application settings"),":causer.name <:causer.email>");
        // activity('SYS-SETTING-UPDATE')
        //     ->causedBy(Auth::user())
        //     ->withProperties($data)
        //     ->log($message);

        return redirect()->route("admin.config.settings");
    }
}
