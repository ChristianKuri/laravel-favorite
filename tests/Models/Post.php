<?php

namespace App;

use ChristianKuri\LaravelFavorite\Traits\Favoriteable;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
   use Favoriteable;

   protected $table = 'posts';
   protected $guarded = [];
}
