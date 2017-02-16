<?php

namespace Ufutx\LaravelFavorite\Test\Models;

use Ufutx\LaravelFavorite\Traits\Favoriteable;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
   use Favoriteable;
   
   protected $table = 'articles';
   protected $guarded = [];
}
