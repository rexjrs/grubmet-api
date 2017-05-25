<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Workshop extends Model
{
   protected $table = 'workshop';

    protected $fillable = [
    	'image',
    	'date',
    	'description'
    ];
}
