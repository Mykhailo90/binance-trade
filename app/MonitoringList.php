<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class MonitoringList extends Model
{

    protected $table = 'monitoring_list';

    protected $fillable = [
        'list_name_id',
        'symbol',
        'min_value',
        'max_value'
    ];

    public $timestamps = false;

    public function listName()
    {
        return $this->hasOne(ListNames::class, 'id', 'list_name_id');
    }
}
