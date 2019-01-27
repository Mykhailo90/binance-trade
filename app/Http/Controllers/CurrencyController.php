<?php

namespace App\Http\Controllers;

use App\Http\Services\CurrencyService;

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

        return response('', 201);
    }

    public function index(CurrencyService $service)
    {
        $binanceList = $service->getBinanceCurrencyList();

        return view('currency-pairs', compact('binanceList'));
    }
}
