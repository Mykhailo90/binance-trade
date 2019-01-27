<?php

namespace App\Http\Services;

use App\CurrencyList;

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
}
