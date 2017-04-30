<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Meals extends Model
{
   protected $table = 'meals';

    protected $fillable = [
    	'image',
    	'meal',
    	'date',
    ];
}
