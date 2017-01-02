<?php

namespace ChristianKuri\LaravelFavorite\Test;

use ChristianKuri\LaravelFavorite\Test\Models\Article;
use ChristianKuri\LaravelFavorite\Test\Models\Post;
use ChristianKuri\LaravelFavorite\Test\Models\User;
use ChristianKuri\LaravelFavorite\Models\Favorite;

class FavoriteModelTest extends TestCase
{
    /** @test */
    public function a_user_can_favorite_a_model()
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
}