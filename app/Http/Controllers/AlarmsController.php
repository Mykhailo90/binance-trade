<?php

namespace App\Http\Controllers;

use App\Http\Services\AlarmsService;
use Illuminate\Http\Request;

class AlarmsController extends Controller
{
    public function index(AlarmsService $alarmsService)
    {
        $oldAlarms = $alarmsService->getOldList();
        $newAlarms = $alarmsService->getNewAlarms();

        return view('alarms', compact('oldAlarms', 'newAlarms'));
    }

    public function saveAlarm(Request $request, AlarmsService $alarmsService)
    {
        if ($id = $request->get('id'))
            $alarmsService->moveToHistory($id);

        return response('', 204);
    }

    public function saveAll(AlarmsService $alarmsService)
    {
        $all = $alarmsService->getNewAlarms();
        foreach ($all as $item){
            $item->status = 'checked';
            $item->save();
        }
    }

    public function deleteAlarm(Request $request, AlarmsService $alarmsService)
    {
        if ($id = $request->get('id'))
            $alarmsService->delete($id);

        return response('', 204);
    }

    public function deleteAll(AlarmsService $alarmsService)
    {
        $all = $alarmsService->getOldList();
        foreach ($all as $item){
            $item->delete();
        }
    }

}
