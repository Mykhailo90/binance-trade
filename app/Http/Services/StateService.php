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

        $obj->state = $params->state;
        $obj->timer = $params->timer;
        $obj->save();
    }

}
