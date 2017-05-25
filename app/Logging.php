<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Logging extends Model
{
   protected $table = 'meals';

    protected $fillable = [
    	'image',
    	'type',
    	'image',
    	'description',
    	'cals',
    	'date'
    ];
}
