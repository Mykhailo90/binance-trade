<?php

namespace App\Http\Services;

use App\AlarmsList;

class AlarmsService
{
    public function getNewAlarms()
    {
        return AlarmsList::where('status', 'new');
    }

    public  function changeAlarmStatus($id)
    {
        $alarm = AlarmsList::find($id);

        $alarm->status = 'checked';
        $alarm->save();
    }


}
