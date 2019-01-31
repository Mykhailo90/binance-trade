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

    public function saveGlobalParams($request)
    {
        $checkCount = $request->get('checkCount');
        $checkSound = $request->get('checkSound');
        $min = $request->get('min');
        $max = $request->get('max');

        $global = GlobalSettings::first();

        if (!$global)
            $global = new GlobalSettings();

        $global->use_sound_alert = $checkSound;
        $global->check_new_pairs = $checkCount;
        $global->min_value = $min;
        $global->max_value = $max;
        $global->save();
    }

}
