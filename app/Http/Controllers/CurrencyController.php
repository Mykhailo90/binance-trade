<?php

namespace App\Http\Controllers;

use App\Cast;
use App\Http\Services\AlarmsService;
use App\Http\Services\CastService;
use App\Http\Services\CurrencyService;
use App\Http\Services\ProcessingService;
use App\Http\Services\SettingsService;
use App\Http\Services\StateService;
use App\ListNames;
use App\MonitoringList;
use Hamcrest\Core\Set;
use Illuminate\Http\Request;

class CurrencyController extends Controller
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
    public function updateList(CurrencyService $service, ProcessingService $processingService)
    {
        $newCurrencyList = $service->getActualList();

        $service->updateCurrencyList($newCurrencyList);
        $service->updateCurrencyListMonitoringStatus();
        $processingService->checkResolution();

        return response('', 204);
    }

    public function index(CurrencyService $service, SettingsService $settingsService, AlarmsService $alarmsService, ProcessingService $processingService)
    {
        $newAlarms = $alarmsService->getNewAlarms();
        $binanceList = $service->getBinanceCurrencyList();
        $checkParams = $settingsService->getGlobalParams();
        $checkParams = ($checkParams) ? 1 : 0;
        $monitoringList = $service->getMonitoringList();
        $listNames = $service->getListNames();
        $processingService->checkResolution();

        return view('currency-pairs', compact('binanceList', 'checkParams', 'monitoringList', 'newAlarms', 'listNames'));
    }

    public function deleteList(Request $request, CurrencyService $service, ProcessingService $procService)
    {
        $idNameList = $request->nameListId;

        $service->deleteList($idNameList);
        $obj = ListNames::find($idNameList);

        $castList = Cast::where('name', $obj->name)->get();

        foreach ($castList as $item){
            $item->delete();
        }

        $procService->checkResolution();


        $obj->delete();

        return response('', 204);
    }

    public function deleteCurrency(Request $request, ProcessingService $procService)
    {
        $monitoringObject = MonitoringList::find($request->id);

        $listName = ListNames::find($monitoringObject->list_name_id)->name;

        $obj = Cast::where('name', $listName)->where('symbol', $monitoringObject->symbol)->first();

        if ($obj)
            $obj->delete();

        if ($monitoringObject)
            $monitoringObject->delete();

        $procService->checkResolution();

        return response('', 204);
    }

    public function updateCurrencySettings(Request $request, SettingsService $service, ProcessingService $processingService)
    {
        $service->updateSettings($request);
        $processingService->checkResolution();

        return response('', 204);
    }

    public function addCurrency(Request $request, CurrencyService $service, SettingsService $settingsService, ProcessingService $processingService)
    {
        $service->addCurrency($request, $settingsService);
        $processingService->checkResolution();
        return response('', 204);
    }

    public function addAllCurrencyList(Request $request, CurrencyService $service, SettingsService $settingsService, ProcessingService $processingService)
    {
        $id = $request->id;
        $service->addAllListToMonitoring($settingsService, $id);
        $processingService->checkResolution();

        return response('', 204);
    }

    public function newListCreate(Request $request, CurrencyService $service, ProcessingService $processingService)
    {
        $service->createMonitoringName($request->get('name'));
        $processingService->checkResolution();

        return response('', 204);
    }
}
