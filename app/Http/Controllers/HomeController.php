<?php

namespace App\Http\Controllers;

use App\Http\Services\CurrencyService;
use App\Http\Services\SettingsService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index(SettingsService $settingsService, AlarmsService $alarmsService, CurrencyService $currencyService)
    {
        $globalParams = $settingsService->getGlobalParams();
        $newAlarms = $alarmsService->get


        return view('welcome', compact('globalParams'));
    }

    public function saveGlobalSettings(Request $request, SettingsService $service)
    {
        $service->saveGlobalParams($request);

        return response('', 204);
    }

}
