<?php

namespace App\Http\Services;

use App\CurrencyList;
use App\MonitoringList;

class CurrencyService
{
    public function getActualList()
    {
        $newList = file("https://api.binance.com/api/v1/exchangeInfo");

        $newList = json_decode($newList[0])->symbols;
        return $newList;
    }

    public function getBinanceCurrencyList()
    {
        $list = CurrencyList::all();

        return $list;
    }

    public function updateCurrencyList($newCurrencyList)
    {
        $aldList = CurrencyList::all();
        foreach ($aldList as $item)
            $item->delete();

        foreach ($newCurrencyList as $item){
            $pair = new CurrencyList();
            $pair->name = $item->symbol;
            $pair->status = $item->status;
            $pair->save();
        }
    }

    public function updateCurrencyListMonitoringStatus()
    {
        $monitoringPairsNames = MonitoringList::all()->pluck('name');
        $monitoringPairsNames = $monitoringPairsNames->toArray();

        CurrencyList::whereIn('name', $monitoringPairsNames)->update(['monitoring' => 1]);
    }

    public function getMonitoringList()
    {
        return MonitoringList::all();
    }

    public function deleteList()
    {
        $list = MonitoringList::all();
        $monitoringPairsNames = ($list->pluck('name'))->toArray();

        CurrencyList::whereIn('name', $monitoringPairsNames)->update(['monitoring' => 0]);

        foreach ($list as $item)
        {
            $item->delete();
        }
    }

    public function deleteCurrency($id)
    {
        $currency = MonitoringList::find($id);

        if ($currency){
            CurrencyList::where('name', $currency->name)->update(['monitoring' => 0]);
            $currency->delete();
        }

    }

    public function addCurrency($request)
    {
        $id = $request->get('id');
        $min = $request->get('min');
        $max = $request->get('max');

        $res = CurrencyList::find($id);
        $res->monitoring = 1;
        $res->save();

        $name = $res->name;

        $currency = new MonitoringList();
        $currency->name = $name;
        $currency->min_value = $min;
        $currency->max_value = $max;
        $currency->save();
    }

    public function addAllListToMonitoring(SettingsService $settingsService)
    {
        $globalParams = $settingsService->getGlobalParams();

        $newList = CurrencyList::where('monitoring', 0)->get();
        foreach ($newList as $item){
            $monitoring = new MonitoringList();
            $monitoring->name = $item->name;
            $monitoring->min_value = $globalParams->min_value;
            $monitoring->max_value = $globalParams->max_value;
            $monitoring->save();
            $item->monitoring = 1;
            $item->save();
        }
    }

}
