<?php

namespace App\Http\Services;

use App\GlobalSettings;

class ProcessingService
{
    public function startWork(CurrencyService $currencyService,
                              CastService $castService,
                              AlarmsService $alarmsService,
                              SettingsService $settingsService,
                              OverviewService $overviewService)
    {
        $settings = $settingsService->getGlobalParams();

        if ($settings->check_new_pairs)
        {
            $oldList = $currencyService->getBinanceCurrencyList();
            $newList = $this->createNewList($currencyService);
            $changes = $this->delta($oldList, $newList);
            $alarmsService->createNewAlarmsByPairs($changes);
        }
        // Main block to parse changes in currency price
        $srcCastData = $castService->getList();
        $newInfo = $castService->createActualPrice();
        $overviewService->createListOverview($srcCastData, $newInfo);
        $alarmsService->createNewAlarmsFromPrice($currencyService, $overviewService);


        if ($settings->use_sound_alert && $alarmsService->getNewAlarms()->count()){


            // Create command to sound alarms
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

    public function checkResolution()
    {
        $currencyService = new CurrencyService();
        $settingsService = new SettingsService();
        $castService = new CastService();
        $stateService = new StateService();

        $monitorinListCount = $currencyService->getMonitoringList()->count();
        $castListCount = $castService->getList();
        $checkSettingsParam = $settingsService->getGlobalParams()->count();
        $obj = new \stdClass();

        if ($monitorinListCount && $castListCount && $checkSettingsParam)
        {
            $obj->resolution = 1;
            $stateService->set($obj);
        }
        else{
            $obj->resolution = 1;
            $stateService->set($obj);
        }
    }

}
