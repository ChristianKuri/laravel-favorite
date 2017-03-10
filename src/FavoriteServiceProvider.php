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
		$this->publishes([
			__DIR__.'/../migrations/create_favorites_table.php' => database_path('migrations/'.date('Y_m_d_His').'_create_favorites_table.php'),
        ], 'migrations');	
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