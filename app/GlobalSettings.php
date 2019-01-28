<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class GlobalSettings extends Model
{

    protected $table = 'globla_parameters_list';

    protected $fillable = [
        'check_new_pairs',
        'min_value',
        'max_value'
    ];

    public $timestamps = false;


}
