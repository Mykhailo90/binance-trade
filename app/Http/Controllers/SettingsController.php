<?php

namespace App\Http\Controllers;

use App\Http\Services\CurrencyService;
use App\Http\Services\SettingsService;
use App\MonitoringList;
use Illuminate\Http\Request;

class SettingsController extends Controller
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

    public function index(SettingsService $service)
    {
        $globalParams = $service->getGlobalParams();

        return view('global-settings', compact('globalParams'));
    }

}
