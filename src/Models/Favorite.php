<?php

namespace ChristianKuri\LaravelFavorite\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

/**
 * This file is part of Laravel Favorite,
 *
 * @license MIT
 * @package ChristianKuri/laravel-favorite
 *
 * Copyright (c) 2016 Christian Kuri
 */
class Favorite extends Model
{
	/**
     * The table associated with the model.
     *
     * @var string
     */
	protected $table = 'favorites';

	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['user_id'];

	/**
     * Define a polymorphic, inverse one-to-one or many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
	public function favoriteable()
	{
		return $this->morphTo();
	}

	public function user()
    {
        return $this->belongsTo(Config::get('auth.providers.users.model'));
    }
}