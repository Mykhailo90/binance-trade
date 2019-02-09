<?php

namespace App\Http\Controllers;

use App\Http\Services\AlarmsService;
use App\Http\Services\ProcessingService;
use App\Http\Services\SettingsService;
use Illuminate\Http\Request;

class SettingsController extends Controller
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

    public function index(SettingsService $service, AlarmsService $alarmsService, ProcessingService $processingService)
    {
        $globalParams = $service->getGlobalParams();
        $newAlarms = $alarmsService->getNewAlarms();
        $processingService->checkResolution();

        return view('global-settings', compact('globalParams', 'newAlarms'));
    }

    public function saveGlobalSettings(Request $request, SettingsService $service, ProcessingService $processingService)
    {
        $service->saveGlobalParams($request);
        $processingService->checkResolution();

        return response('', 204);
    }

}
