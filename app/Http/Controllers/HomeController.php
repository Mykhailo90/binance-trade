<?php

namespace App\Http\Controllers;

use App\Http\Services\AlarmsService;
use App\Http\Services\CastService;
use App\Http\Services\CurrencyService;
use App\Http\Services\ProcessingService;
use App\Http\Services\SettingsService;
use App\Http\Services\StateService;
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

    public function index(SettingsService $settingsService,
                          AlarmsService $alarmsService,
                          CurrencyService $currencyService,
                          CastService $castService,
                          StateService $stateService,
                          ProcessingService $processingService)
    {
        $globalParams = $settingsService->getGlobalParams();
        $newAlarms = $alarmsService->getNewAlarms();
        $countCurrency = $currencyService->getMonitoringList()->count();
        $countAlarms = $newAlarms->count();
        $cast = $castService->getList();
        $monitoringState = $stateService->get();

        $processingService->checkResolution();

//        $cmd=exec(escapeshellcmd('/usr/bin/mpg123 /home/slaven/Загрузки/beep-01a.mp3'));

        return view('welcome', compact('globalParams', 'cast', 'countCurrency', 'countAlarms', 'monitoringState', 'newAlarms'));
    }

    public function saveGlobalSettings(Request $request, SettingsService $service, ProcessingService $processingService)
    {
        $service->saveGlobalParams($request);
        $processingService->checkResolution();

        return response('', 204);
    }

}
