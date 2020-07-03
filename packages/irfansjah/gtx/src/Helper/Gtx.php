<?php
namespace Irfansjah\Gtx\Helper;
use Illuminate\Support\Facades\Route;

class Gtx {
    public function RegisterRoute() {
        Route::middleware(['permission:system_setting'])->post('/settings', '\Irfansjah\Gtx\Controllers\Admin\SettingController@store')->middleware('auth:admin')->name('config.store');
        Route::middleware(['permission:system_setting'])->get('/settings', '\Irfansjah\Gtx\Controllers\Admin\SettingController@index')->middleware('auth:admin')->name('config.settings');

        Route::middleware(['permission:show_log'])->get('/log','\Irfansjah\Gtx\Controllers\Admin\LogManagerController@Index')->name('logmanager');
        Route::middleware(['permission:show_log'])->get('/log/channels','\Irfansjah\Gtx\Controllers\Admin\LogManagerController@GetLogChannel')->middleware('auth:admin')->name('logs.channels');
        Route::middleware(['permission:show_log'])->get('/log/load','\Irfansjah\Gtx\Controllers\Admin\LogManagerController@GetLogFiles')->middleware('auth:admin')->name('logs.load');
        Route::middleware(['permission:delete_log'])->get('/log/delete','\Irfansjah\Gtx\Controllers\Admin\LogManagerController@DeleteLogFiles')->middleware('auth:admin')->name('logs.delete');
        Route::middleware(['permission:download_log'])->get('/log/download','\Irfansjah\Gtx\Controllers\Admin\LogManagerController@DownloadLogFiles')->middleware('auth:admin')->name('logs.download');

        Route::middleware(['permission:file_manager'])->get('file-manager', '\Irfansjah\Gtx\Controllers\Admin\FileManagementController@index')->name('filemanager');

    }

    public function CreateSetting($group, $name, $value) {
        \Irfansjah\Gtx\Models\SystemConfig::create([
            'name'=> $name,
            'value'=>$value,
            'group'=> $group
        ]);
    }
}
