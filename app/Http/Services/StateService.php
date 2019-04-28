<?php

namespace App\Http\Services;

use App\State;

class StateService
{
    public function get()
    {
        return State::first();
    }

    public function set($params)
    {
        $obj = State::first();

        if (!$obj)
            $obj = new State();

        if (isset($params->state))
            $obj->state = ($params->state) ? $params->state : 0;

        if (isset($params->timer))
            $obj->timer = ($params->timer) ? $params->timer: 0;

        if (isset($params->resolution))
            $obj->resolution = $params->resolution ? $params->resolution : 0;

        $obj->save();
    }

}
