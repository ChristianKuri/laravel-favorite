<?php

namespace ChristianKuri\LaravelFavorite\Traits;

use ChristianKuri\LaravelFavorite\Models\Favorite;
use Illuminate\Support\Facades\Auth;

/**
 * This file is part of Laravel Favorite,
 *
 * @license MIT
 * @package ChristianKuri/laravel-favorite
 *
 * Copyright (c) 2016 Christian Kuri
 */
trait Favoriteable
{
	/**
     * Define a polymorphic one-to-many relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
	public function favorites()
	{
		return $this->morphMany(Favorite::class, 'favoriteable');
	}

	/**
	 * Add this Object to the user favorites
	 * 
	 * @param  int $user_id  [if  null its added to the auth user]
	 */
	public function addFavorite($user_id = null)
	{
		$favorite = new Favorite(['user_id' => ($user_id) ? $user_id : Auth::id()]);
		$this->favorites()->save($favorite);
	}

	/**
	 * Remove this Object from the user favorites
	 *
	 * @param  int $user_id  [if  null its added to the auth user]
	 * 
	 */
	public function removeFavorite($user_id = null)
	{
		$this->favorites()->where('user_id', ($user_id) ? $user_id : Auth::id())->delete();
	}

	/**
	 * Toggle the favorite status from this Object
	 * 
	 * @param  int $user_id  [if  null its added to the auth user]
	 */
	public function toggleFavorite($user_id = null)
	{
		$this->isFavorited($user_id) ? $this->removeFavorite($user_id) : $this->addFavorite($user_id) ;
	}

	/**
	 * Check if the user has favorited this Object
	 * 
	 * @param  int $user_id  [if  null its added to the auth user]
	 * @return boolean
	 */
	public function isFavorited($user_id = null)
	{
		return $this->favorites()->where('user_id', ($user_id) ? $user_id : Auth::id())->exists();
	}

	/**
     * Return a collection with the Users who marked as favorite this Object.
     * 
     * @return Collection
     */
	public function favoritedBy()
	{
		return $this->favorites()->with('user')->get()->mapWithKeys(function ($item) {
            return [$item['user']];
        });
	}

	/**
	 * Count the number of favorites
	 * 
	 * @return int
	 */
	public function getFavoritesCountAttribute()
	{
		return $this->favorites()->count();
	}

	/**
	 * @return favoritesCount attribute
	 */
	public function favoritesCount()
	{
		return $this->favoritesCount;
	}
}