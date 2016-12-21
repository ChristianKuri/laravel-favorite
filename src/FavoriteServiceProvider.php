<?php

namespace ChristianKuri\LaravelFavorite;

use Illuminate\Support\ServiceProvider;

/**
 * This file is part of Laravel Favorite,
 *
 * @license MIT
 * @package ChristianKuri/laravel-favorite
 *
 * Copyright (c) 2016 Christian Kuri
 */
class FavoriteServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap the application services.
	 * 
	 * @return void
	 */
	public function boot()
	{
		$this->loadMigrationsFrom(__DIR__.'/../migrations');
	}

	/**
	 * Register the application services.
	 * 
	 * @return void
	 */
	public function register()
	{
		# code...
	}
}