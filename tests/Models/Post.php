<?php

namespace ChristianKuri\LaravelFavorite\Test\Models;

use ChristianKuri\LaravelFavorite\Traits\Favoriteable;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
   use Favoriteable;

   protected $table = 'posts';
   protected $guarded = [];
}
