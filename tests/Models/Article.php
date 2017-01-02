<?php

namespace App;

use ChristianKuri\LaravelFavorite\Traits\Favoriteable;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
   use Favoriteable;
   
   protected $table = 'articles';
   protected $guarded = [];
}
