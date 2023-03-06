<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Route;

Route::get('/{package}-redirect', function ($package) {
    // GET THE STATE & PARSE IT:
    $state = json_decode(request('state'), true);
    // GET THE SETTING:
    $setting = Setting::find($state['setting_id'] ?? null);
    // GET THE CLASS NAME:
    $class = ucfirst($package);
    // GET THE SERVICE INSTANCE:
    $serviceInstance = call_user_func(["Clickonmedia\\{$class}\\{$class}", 'new'], compact('setting'));
    // GET THE SERVICE:
    $service = $serviceInstance->read();
    // GET THE USER:
    $user = $serviceInstance->user();
    // GET THE INTERFACE TYPE:
    $interfaceType = $serviceInstance->getInterfaceType();
    // REDIRECT TO THE CREATE ROUTE:
    return redirect()->route('service.create', compact('service', 'setting', 'user', 'interfaceType'));
});
