<?php

namespace App\Http\Controllers;

use App\Http\Services\CurrencyService;
use App\Http\Services\SettingsService;
use App\MonitoringList;
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

    public function index(CurrencyService $service, SettingsService $settingsService)
    {
        $binanceList = $service->getBinanceCurrencyList();
        $checkParams = $settingsService->getGlobalParams();
        $checkParams = ($checkParams) ? 1 : 0;
        $monitoringList = $service->getMonitoringList();

        return view('currency-pairs', compact('binanceList', 'checkParams', 'monitoringList'));
    }

    public function deleteList(CurrencyService $service)
    {
        $service->deleteList();
        $list = MonitoringList::all();
        foreach ($list as $item)
        {
            $item->delete();
        }
        return response('', 204);
    }

    public function deleteCurrency(Request $request, CurrencyService $service)
    {
        $service->deleteCurrency($request->id);

        return response('', 204);
    }

    public function updateCurrencySettings(Request $request, SettingsService $service)
    {
        $service->updateSettings($request);
        return response('', 204);
    }

    public function addCurrency(Request $request, CurrencyService $service)
    {
        $service->addCurrency($request);
        return response('', 204);
    }

    public function addAllCurrencyList(CurrencyService $service, SettingsService $settingsService)
    {
        $service->addAllListToMonitoring($settingsService);
        return response('', 204);
    }
}
