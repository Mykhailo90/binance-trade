<?php

namespace App\Http\Services;

use App\CurrencyList;
use App\ListNames;
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
        $monitoringPairsNames = MonitoringList::all()->pluck('symbol');
        $monitoringPairsNames = $monitoringPairsNames->toArray();

        CurrencyList::whereIn('name', $monitoringPairsNames)->update(['monitoring' => 1]);
    }

    public function getMonitoringList()
    {
        return MonitoringList::with('listName')->get();
    }

    public function deleteList($idListName)
    {
        $list = MonitoringList::where('list_name_id', $idListName)->get();

        foreach ($list as $item)
        {
            $item->delete();
        }

    }

    public function deleteCurrency($id)
    {
        $currency = MonitoringList::find($id);

        if ($currency){
            $currency->delete();
        }

    }

    public function addCurrency($request, SettingsService $settingsService)
    {
        $id = $request->get('id');
        $min = ($request->get('min')) ? $request->get('min') : $settingsService->getGlobalParams()->min_value;
        $max = $request->get('max') ? $request->get('max') : $settingsService->getGlobalParams()->max_value;
        $listId = $request->get('listId');


        $res = CurrencyList::find($id);
        $name = $res->name;

        $currency = MonitoringList::where('list_name_id', $listId)->where('symbol', $name)->first();

        if (!$currency)
            $currency = new MonitoringList();
        $currency->list_name_id = $listId;
        $currency->symbol = $name;
        $currency->min_value = $min;
        $currency->max_value = $max;
        $currency->save();
    }

    public function addAllListToMonitoring(SettingsService $settingsService, $id)
    {
        $globalParams = $settingsService->getGlobalParams();

        $newList = CurrencyList::all();
        $oldList = MonitoringList::where('list_name_id', $id)->get();

        foreach ($newList as $item){

            if($oldList){
                foreach ($oldList as $old){
                    if ($old->symbol == $item->name)
                        break;
                }
            }
            $monitoring = new MonitoringList();
            $monitoring->list_name_id = $id;
            $monitoring->symbol = $item->name;
            $monitoring->min_value = $globalParams->min_value;
            $monitoring->max_value = $globalParams->max_value;
            $monitoring->save();
        }
    }

    public function createMonitoringName($name)
    {
        $obj = ListNames::where('name', $name)->first();

        if (!$obj){
            $obj = new ListNames();
            $obj->name = $name;
            $obj->save();
        }
    }

    public function getListNames()
    {
        return ListNames::all();
    }

}
