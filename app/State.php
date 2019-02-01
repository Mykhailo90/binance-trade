<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{

    protected $table = 'monitoring_state';

    protected $fillable = [
        'state',
        'timer',
    ];

    public $timestamps = false;

}
