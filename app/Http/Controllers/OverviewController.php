<?php

namespace App\Http\Controllers;

use App\Http\Services\AlarmsService;
use App\Http\Services\OverviewService;

class OverviewController extends Controller
{
    public function index(OverviewService $service, AlarmsService $alarmsService)
    {
        $overview = $service->getList();
        $newAlarms = $alarmsService->getNewAlarms();

        return view('overview', compact('overview', 'newAlarms'));
    }

}
