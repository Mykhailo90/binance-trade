<?php

namespace App\Http\Services;

class ProcessingService
{
    public function startWork(CurrencyService $currencyService,
                              CastService $castService,
                              AlarmsService $alarmsService,
                              SettingsService $settingsService)
    {
        $settings = $settingsService->getGlobalParams();
        // $settings->use_sound_alert
        // $settings->check_new_pairs

        if ($settings->check_new_pairs)
        {
            $oldList = $currencyService->getBinanceCurrencyList();
            $newList = $this->createNewList($currencyService);
            $changes = $this->delta($oldList, $newList);
            $count = $alarmsService->createNewAlarmsByPairs($changes);

            dd($changes);

        }
    }


    private function delta($oldList, $newList)
    {
        $oldNames = $oldList->pluck('name')->toArray();
        $newNames = [];
        $all = [];
        $changes = [];

        foreach ($newList as $item){
            $newNames[] = $item->name;
        }

        $names = array_merge($oldNames, $newNames);
        $namesUnique = array_unique($names);

        foreach ($namesUnique as $name)
        {
            $obj = new \stdClass();
            $obj->name = $name;
            $obj->status['old'] = $this->getStatus($name, $oldList);
            $obj->status['new'] = $this->getStatus($name, $newList);

            $all[] = $obj;
        }

        foreach ($all as $one){
            if ($one->status['old'] != $one->status['new'])
                $changes[] = $one;
        }

        return $changes;
    }

    private function getStatus($name, $list)
    {
        foreach ($list as $item){
            if ($item->name == $name)
                return $item->status;
        }

        return NULL;
    }

    private function createNewList(CurrencyService $currencyService): array
    {
        $newList = $currencyService->getActualList();
        $actual = [];
        foreach ($newList as $item){
            $obj = new \stdClass();

            $obj->name = $item->symbol;
            $obj->status = $item->status;
            $actual[] = $obj;
        }

        return $actual;
    }

}
