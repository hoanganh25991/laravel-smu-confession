<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    protected $guarded = ['id'];
    protected $fillable = ['provider_id', 'role'];
    
    
}
