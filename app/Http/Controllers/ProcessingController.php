<?php

namespace App\Http\Controllers;

use App\Http\Services\AlarmsService;
use App\Http\Services\CastService;
use App\Http\Services\CurrencyService;
use App\Http\Services\ProcessingService;
use App\Http\Services\SettingsService;
use App\Http\Services\StateService;
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
                                           SettingsService $settingsService)
    {
        if ($request && isset($request->state) && $request->state == 1)
        {
            $stateService->set($request);
            $processingService->startWork($currencyService, $castService, $alarmsService, $settingsService);
        }
        elseif (($stateService->get()) && $stateService->get()->state == 1) {
            $processingService->startWork($currencyService, $castService, $alarmsService, $settingsService);
        }
    }

   public function castCreate(Request $request, CastService $castService, CurrencyService $currencyService)
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

       return response('', 204);
   }

    public function castDelete(Request $request, CastService $castService)
    {
        $id = $request->get('id');

        if ($id)
            $castService->delete($id);

        return response('', 204);
    }

   public function index(CastService $castService)
   {
       $allCast = $castService->getList();
       $castNames = $allCast->pluck('name')->unique();

       return view('cast', compact('allCast', 'castNames'));
   }

}
