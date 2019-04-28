<?php

namespace App\Http\Services;

use App\Overview;

class OverviewService
{
    public function getList()
    {
        return (Overview::all())->sortBy('percent_change');
    }

    public function clearList()
    {
        Overview::truncate();
    }

    public function createListOverview($srcCastData, $newInfo)
    {
        $this->clearList();

        foreach ($srcCastData as $src) {
            foreach ($newInfo as $new) {
                if ($src->symbol == $new->symbol) {
                    $obj = new Overview();
                    $obj->cast_name = $src->name;
                    $obj->symbol = $src->symbol;
                    $obj->first_price = $src->last_price;
                    $obj->price = $new->lastPrice;
                    if ($new->lastPrice == 0)
                        $obj->percent_change = 0;
                    else{
                        $obj->percent_change = ($src->last_price < $new->lastPrice) ?
                            abs(100*($src->last_price - $new->lastPrice)/$new->lastPrice) :
                            -1*abs(100*($src->last_price - $new->lastPrice)/$new->lastPrice);
                    }

                    $obj->save();
                    break;
                }
            }
        }
    }
}
