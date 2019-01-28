<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class MonitoringList extends Model
{

    protected $table = 'monitoring_list';

    protected $fillable = [
        'name',
        'min_value',
        'max_value'
    ];

    public $timestamps = false;


}
