<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Cast extends Model
{

    protected $table = 'currency_cast';

    protected $fillable = [
        'name',
        'symbol',
        'daily_price_change_persent',
        'last_price',
        'high_price',
        'low_price',
        'count',
    ];

    protected $dates = ['date'];

    public $timestamps = false;


}
