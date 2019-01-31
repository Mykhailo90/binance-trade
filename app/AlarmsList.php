<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class AlarmsList extends Model
{

    protected $table = 'alarms_list';

    protected $fillable = [
        'title',
        'pair_name',
        'text',
        'status',
        'date'
    ];

    protected $dates = ['date'];

    public $timestamps = false;


}
