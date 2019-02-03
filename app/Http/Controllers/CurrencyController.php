<?php

namespace App\Http\Controllers;

use App\Cast;
use App\Http\Services\AlarmsService;
use App\Http\Services\CastService;
use App\Http\Services\CurrencyService;
use App\Http\Services\SettingsService;
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
    public function updateList(CurrencyService $service)
    {
        $newCurrencyList = $service->getActualList();

        $service->updateCurrencyList($newCurrencyList);
        $service->updateCurrencyListMonitoringStatus();

        return response('', 204);
    }

    public function index(CurrencyService $service, SettingsService $settingsService, AlarmsService $alarmsService)
    {
        $newAlarms = $alarmsService->getNewAlarms();
        $binanceList = $service->getBinanceCurrencyList();
        $checkParams = $settingsService->getGlobalParams();
        $checkParams = ($checkParams) ? 1 : 0;
        $monitoringList = $service->getMonitoringList();
        $listNames = $service->getListNames();

        return view('currency-pairs', compact('binanceList', 'checkParams', 'monitoringList', 'newAlarms', 'listNames'));
    }

    public function deleteList(Request $request, CurrencyService $service, CastService $castService)
    {
        $idNameList = $request->nameListId;
        $service->deleteList($idNameList);
        $obj = ListNames::find($idNameList);

        $castList = Cast::where('name', $obj->name)->get();
//        $list = MonitoringList::all();

        foreach ($castList as $item){
            $item->delete();
        }


        $obj->delete();

        return response('', 204);
    }

    public function deleteCurrency(Request $request)
    {
        $monitoringObject = MonitoringList::find($request->id);

        $listName = ListNames::find($monitoringObject->list_name_id)->name;

        $obj = Cast::where('name', $listName)->where('symbol', $monitoringObject->symbol)->first();

        if ($obj)
            $obj->delete();

        if ($monitoringObject)
            $monitoringObject->delete();

        return response('', 204);
    }

    public function updateCurrencySettings(Request $request, SettingsService $service)
    {
        $service->updateSettings($request);
        return response('', 204);
    }

    public function addCurrency(Request $request, CurrencyService $service, SettingsService $settingsService)
    {
        $service->addCurrency($request, $settingsService);
        return response('', 204);
    }

    public function addAllCurrencyList(Request $request, CurrencyService $service, SettingsService $settingsService)
    {
        $id = $request->id;
        $service->addAllListToMonitoring($settingsService, $id);
        return response('', 204);
    }

    public function newListCreate(Request $request, CurrencyService $service)
    {
        $service->createMonitoringName($request->get('name'));

        return response('', 204);
    }
}
