<?php

namespace App\Http\Controllers;

use App\Http\Services\AlarmsService;

class AlarmsController extends Controller
{
    public function index(AlarmsService $alarmsService)
    {
        $oldAlarms = $alarmsService->getOldList();
        $newAlarms = $alarmsService->getNewAlarms();

        return view('alarms', compact('oldAlarms', 'newAlarms'));
    }

}
