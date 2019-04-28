<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Overview extends Model
{

    protected $table = 'overview';

    protected $fillable = [
        'cast_name',
        'symbol',
        'first_price',
        'price',
        'percent_change'
    ];

    public $timestamps = false;

}
