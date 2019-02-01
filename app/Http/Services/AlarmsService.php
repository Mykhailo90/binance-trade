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

    public function createNewAlarmsByPairs($changes)
    {
        $count = 0;

        foreach ($changes as $item){
            $alarm = new AlarmsList();
            $alarm->title = 'Изменения в парах';
            $alarm->pair_name = $item->name;
            if (!$item->status['old'])
                $alarm->text = 'На рынке новая монета!!!';
            elseif (!$item->status['new'])
                $alarm->text = 'Монета больше не торгуется!!!';
            else
                $alarm->text = "Статус монеты изменился (".$item->status['old'].")-(".$item->status['new'].")";

            $alarm->save();
            $count++;
        }

        return $count;
    }

}
