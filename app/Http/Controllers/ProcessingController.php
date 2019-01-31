<?php

namespace App\Http\Controllers;

use App\Http\Services\CastService;
use App\Http\Services\CurrencyService;
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
        $name = $request->get('castName');

        if ($name)
            $castService->delete($name);

        return response('', 204);
    }

   public function index(CastService $castService)
   {
       $allCast = $castService->getList();
       $castNames = $allCast->pluck('name')->unique();

       return view('cast', compact('allCast', 'castNames'));
   }

}
