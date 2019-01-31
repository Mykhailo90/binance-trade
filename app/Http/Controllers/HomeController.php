<?php

namespace App\Http\Controllers;

use App\Http\Services\AlarmsService;
use App\Http\Services\CastService;
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

    public function index(SettingsService $settingsService, AlarmsService $alarmsService, CurrencyService $currencyService, CastService $castService)
    {
        $globalParams = $settingsService->getGlobalParams();
        $newAlarms = $alarmsService->getNewAlarms();
        $countCurrency = $currencyService->getMonitoringList()->count();
        $countAlarms = $newAlarms->count();
        $cast = $castService->getList();

        return view('welcome', compact('globalParams', 'cast', 'countCurrency', 'countAlarms'));
    }

    public function saveGlobalSettings(Request $request, SettingsService $service)
    {
        $service->saveGlobalParams($request);

        return response('', 204);
    }

}
