<?php

namespace App\Http\Services;

use App\GlobalSettings;
use App\MonitoringList;

class SettingsService
{
    public function getGlobalParams()
    {
        return GlobalSettings::first();
    }

    public function updateSettings($request)
    {
        $id = $request->get('id');
        $min = $request->get('min');
        $max = $request->get('max');

        $currency = MonitoringList::find($id);

        if ($min)
            $currency->min_value = $min;
        if ($max)
            $currency->max_value = $max;
        $currency->save();
    }

}
