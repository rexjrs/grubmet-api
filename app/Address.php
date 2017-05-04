<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'address';

    protected $fillable = [
    	'user_id',
    	'line_one',
    	'line_two',
    	'city',
    	'country',
    	'phone',
    	'first_name',
    	'last_name',
    	'zip_code'
    ];
}
