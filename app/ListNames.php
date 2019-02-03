<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class ListNames extends Model
{

    protected $table = 'list_names';

    protected $fillable = [
        'name',
    ];

    

    public $timestamps = false;

}
