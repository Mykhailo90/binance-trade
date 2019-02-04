<?php

namespace App\Http\Controllers;

use App\Http\Services\AlarmsService;
use App\Http\Services\CastService;
use App\Http\Services\CurrencyService;
use App\Http\Services\OverviewService;
use App\Http\Services\ProcessingService;
use App\Http\Services\SettingsService;
use App\Http\Services\StateService;
use App\ListNames;
use Illuminate\Http\Request;

class ProcessingController extends Controller
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

    public function stopMonitoringProcess(StateService $stateService)
    {
        $obj = new \stdClass();
        $obj->state = 0;
        $obj->timer = 30;

        $stateService->set($obj);
    }

    public function startMonitoringProcess(Request $request,
                                           StateService $stateService,
                                           ProcessingService $processingService,
                                           CurrencyService $currencyService,
                                           CastService $castService,
                                           AlarmsService $alarmsService,
                                           SettingsService $settingsService,
                                           OverviewService $overviewService)
    {
        $processingService->checkResolution();

        if ($request && $stateService->get() && $stateService->get()->resolution == 1)
        {
            $stateService->set($request);
            $processingService->startWork($currencyService, $castService, $alarmsService, $settingsService, $overviewService);
        }
//        elseif (($stateService->get()) && $stateService->get()->state == 1) {
//            $processingService->startWork($currencyService, $castService, $alarmsService, $settingsService, $overviewService);
//        }
    }

   public function castCreate(Request $request, CastService $castService, CurrencyService $currencyService, ProcessingService $processingService)
   {
       $castName = $request->castName;

       $monitoringList = $currencyService->getMonitoringList()->pluck('name');

       $binanceInfo = $castService->createActualPrice();

       foreach ($monitoringList as $symbol) {
                   foreach ($binanceInfo as $item) {
                       if ($item->symbol == $symbol){
                           $castService->createCast($castName, $item);
                           break;
                       }
                   }
       }

       $processingService->checkResolution();

       return response('', 204);
   }

    public function castDelete(Request $request, CastService $castService, ProcessingService $processingService)
    {
        $id = $request->get('castName');

        if ($id)
            $castService->delete($id);

        $processingService->checkResolution();

        return response('', 204);
    }

   public function index(CastService $castService, AlarmsService $alarmsService, ProcessingService $processingService)
   {
       $allCast = $castService->getList();
       $newAlarms = $alarmsService->getNewAlarms();
       $castNames = $allCast->pluck('name')->unique();
       $processingService->checkResolution();
       $listNames = ListNames::all();

       return view('cast', compact('allCast', 'castNames', 'newAlarms', 'listNames'));
   }

}
