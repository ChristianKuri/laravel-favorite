<?php

namespace ChristianKuri\LaravelFavorite\Test;

use ChristianKuri\LaravelFavorite\Test\Models\Article;
use ChristianKuri\LaravelFavorite\Test\Models\Post;
use ChristianKuri\LaravelFavorite\Test\Models\User;
use ChristianKuri\LaravelFavorite\Models\Favorite;

class FavoriteModelTest extends TestCase
{
    /** @test */
    public function models_can_add_to_favorites_with_auth_user()
    {
        $article = Article::first();
        $user = User::first();
        $this->be($user);

        $article->addFavorite();

        $this->seeInDatabase('favorites', [
            'user_id' => $user->id,
            'favoriteable_id' => $article->id,
            'favoriteable_type' => get_class($article)
        ]);

        $this->assertTrue($article->isFavorited());
    }

    /** @test */
    public function models_can_remove_from_favorites_with_auth_user()
    {
        $article = Article::first();
        $user = User::first();
        $this->be($user);

        $article->removeFavorite();

        $this->notSeeInDatabase('favorites', [
            'user_id' => $user->id,
            'favoriteable_id' => $article->id,
            'favoriteable_type' => get_class($article)
        ]);

        $this->assertFalse($article->isFavorited());
    }

    /** @test */
    public function models_can_toggle_their_favorite_status_with_auth_user()
    {
        $article = Article::first();
        $user = User::first();
        $this->be($user);

        $article->toggleFavorite();

        $this->assertTrue($article->isFavorited());

        $article->toggleFavorite();

        $this->assertFalse($article->isFavorited());
    }

    /** @test */
    public function models_can_add_to_favorites_without_the_auth_user()
    {
        $post = Post::first();
        $post->addFavorite(2);

        $this->seeInDatabase('favorites', [
            'user_id' => 2,
            'favoriteable_id' => $post->id,
            'favoriteable_type' => get_class($post)
        ]);

        $this->assertTrue($post->isFavorited(2));
    }

    /** @test */
    public function models_can_remove_from_favorites_without_the_auth_user()
    {
        $post = Post::first();
        $post->removeFavorite(2);

        $this->notSeeInDatabase('favorites', [
            'user_id' => 2,
            'favoriteable_id' => $post->id,
            'favoriteable_type' => get_class($post)
        ]);

        $this->assertFalse($post->isFavorited(2));
    }

    /** @test */
    public function models_can_toggle_their_favorite_status_without_the_auth_user()
    {
        $post = Post::first();
        $post->toggleFavorite(2);

        $this->assertTrue($post->isFavorited(2));

        $post->toggleFavorite(2);

        $this->assertFalse($post->isFavorited(2));
    }

    /** @test */
    public function user_model_can_add_to_favorites_other_models()
    {
        $user = User::first();
        $article = Article::first();

        $user->addFavorite($article);

        $this->seeInDatabase('favorites', [
            'user_id' => $user->id,
            'favoriteable_id' => $article->id,
            'favoriteable_type' => get_class($article)
        ]);

        $this->assertTrue($user->hasFavorited($article));
    }

    /** @test */
    public function user_model_can_remove_from_favorites_another_models()
    {
        $user = User::first();
        $article = Article::first();

        $user->removeFavorite($article);

        $this->notSeeInDatabase('favorites', [
            'user_id' => $user->id,
            'favoriteable_id' => $article->id,
            'favoriteable_type' => get_class($article)
        ]);

        $this->assertFalse($user->isFavorited($article));
    }

    /** @test */
    public function user_model_can_toggle_his_favorite_models()
    {
        $user = User::first();
        $article = Article::first();

        $user->toggleFavorite($article);

        $this->assertTrue($user->hasFavorited($article));

        $user->toggleFavorite($article);

        $this->assertFalse($user->isFavorited($article));
    }
}