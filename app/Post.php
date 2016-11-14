<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $guarded = ['id'];
    protected $fillable = ['content', 'photo_path', 'status', 'facebook_page_id'];
}
