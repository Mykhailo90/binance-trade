<?php

namespace App\Http\Services;

use App\AlarmsList;

class AlarmsService
{
    public function getNewAlarms()
    {
        return AlarmsList::where('status', 'new')->get();
    }

    public  function moveToHistory($id)
    {
        $alarm = AlarmsList::find($id);

        $alarm->status = 'checked';
        $alarm->save();
    }

    public  function delete($id)
    {
        $alarm = AlarmsList::find($id);

        $alarm->delete();
    }

    public function getOldList()
    {
        return AlarmsList::where('status', 'checked')->get();
    }

    public function createNewAlarmsByPairs($changes)
    {

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
        }
    }

    public function createNewAlarmsFromPrice(CurrencyService $currencyService, OverviewService $overviewService)
    {
        $settingsInfo = $currencyService->getMonitoringList();
        $overviewList = $overviewService->getList();

        foreach ($overviewList as $new){
            foreach ($settingsInfo as $old){
                if ($new->symbol == $old->name){
                    $this->checkNewAlarm($new, $old);
                    break;
                }
            }
        }
    }

    private function checkNewAlarm($new, $old)
    {
        if ($new->percent_change > 0 && $old->max_value <= $new->percent_change)
        {
            $alarm = new AlarmsList();
            $alarm->title = $new->cast_name;
            $alarm->pair_name = $new->symbol;
            $alarm->text = 'Рост на '. $new->percent_change . '%! (' . $new->first_price . ')-(' . $new->price .') ';
            $alarm->save();
        }
        elseif($new->percent_change < 0){

            $absNegativeChange = abs($new->percent_change);

            if ($old->min_value <= $absNegativeChange) {
                $alarm = new AlarmsList();
                $alarm->title = $new->cast_name;
                $alarm->pair_name = $new->symbol;
                $alarm->text = 'Падение на ' . $new->percent_change . '%! (' . $new->first_price . ')-(' . $new->price . ') ';
                $alarm->save();
            }
        }
    }
}
