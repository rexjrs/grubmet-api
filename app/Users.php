<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'username', 
        'email', 
        'firstname', 
        'lastname', 
        'profile_image', 
        'phone', 
        'dob', 
        'gender', 
        'password', 
        'account_type',
        'social_id'
    ];
}
