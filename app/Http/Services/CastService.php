<?php

namespace App\Http\Services;

use App\Cast;
use Carbon\Carbon;

class CastService
{
    public function getList()
    {
        return Cast::all();
    }

    public  function getCastByName($name)
    {
        return Cast::where('name', $name)->get();
    }

    public function delete($name)
    {
        $casts = $this->getCastByName($name);

        foreach ($casts as $item)
            $item->delete();
    }

    public function delBySymbol($symbol)
    {
        $casts = Cast::where('symbol', $symbol)->get();
        foreach ($casts as $item){
            $item->delete();
        }
    }

    public function createActualPrice()
    {
        $info = file("https://api.binance.com/api/v1/ticker/24hr");

        $info = json_decode($info[0]);

      return $info;
    }

    public function createCast($castName, $item)
    {
        $cast = new Cast();

        $cast->name = $castName;
        $cast->symbol = $item->symbol;
        $cast->daily_price_change_persent = $item->priceChangePercent;
        $cast->last_price = $item->lastPrice;
        $cast->high_price = $item->highPrice;
        $cast->low_price = $item->lowPrice;
        $cast->count = $item->count;
        $cast->date = Carbon::now()->toDateTimeString();
        $cast->save();
    }

}
