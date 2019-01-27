<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class CurrencyList extends Model
{

    protected $table = 'currency_list';

    protected $fillable = [
        'name',
        'status'
    ];

    public $timestamps = false;


}
