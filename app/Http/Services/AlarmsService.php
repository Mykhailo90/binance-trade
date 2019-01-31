<?php

namespace App\Http\Services;

use App\AlarmsList;

class AlarmsService
{
    public function getNewAlarms()
    {
        return AlarmsList::where('status', 'new')->get();
    }

    public  function changeAlarmStatus($id)
    {
        $alarm = AlarmsList::find($id);

        $alarm->status = 'checked';
        $alarm->save();
    }

    public function getOldList()
    {
        return AlarmsList::where('status', 'checked')->get();
    }

}
