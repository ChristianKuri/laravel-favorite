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

        $this->assertDatabaseHas('favorites', [
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

        $this->assertDatabaseMissing('favorites', [
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

        $this->assertDatabaseHas('favorites', [
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

        $this->assertDatabaseMissing('favorites', [
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

        $this->assertDatabaseHas('favorites', [
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

        $this->assertDatabaseMissing('favorites', [
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

    /** @test */
    public function a_user_can_return_his_favorited_models()
    {
        $user = User::first();

        $article1 = Article::find(1);
        $article2 = Article::find(2);
        $article3 = Article::find(3);

        $post1 = Post::find(1);
        $post2 = Post::find(2);

        $user->addFavorite($article1);
        $user->addFavorite($article2);
        $user->addFavorite($article3);

        $user->addFavorite($post1);
        $user->addFavorite($post2);

        $this->assertEquals(3, $user->favorite(Article::class)->count());
        $this->assertEquals(2, $user->favorite(Post::class)->count());

        $user->removeFavorite($article1);
        $user->removeFavorite($article2);
        $user->removeFavorite($article3);

        $user->removeFavorite($post1);
        $user->removeFavorite($post2);

        $this->assertEquals(0, $user->favorite(Article::class)->count());
        $this->assertEquals(0, $user->favorite(Post::class)->count());
    }

    /** @test */
    public function a_model_knows_how_many_users_have_favorited_him()
    {
        $article = Article::first();

        $article->toggleFavorite(1);
        $article->toggleFavorite(2);
        $article->toggleFavorite(3);

        $this->assertEquals(3, $article->favoritesCount());

        $article->toggleFavorite(1);
        $article->toggleFavorite(2);
        $article->toggleFavorite(3);

        $this->assertEquals(0, $article->favoritesCount());
    }

    /** @test */
    public function a_model_knows_which_users_have_favorited_him()
    {
        $article = Article::first();

        $article->toggleFavorite(1);
        $article->toggleFavorite(2);
        $article->toggleFavorite(3);

        $this->assertEquals(3, $article->favoritedBy()->count());

        $article->toggleFavorite(1);
        $article->toggleFavorite(2);
        $article->toggleFavorite(3);

        $this->assertEquals(0, $article->favoritedBy()->count());
    }

    /** @test */
    public function a_user_not_return_favorites_deleteds()
    {
        $user = User::first();

        $article1 = Article::find(1);
        $article2 = Article::find(2);

        $user->addFavorite($article1);
        $user->addFavorite($article2);

        $article1->delete();

        $this->assertEquals(1, $user->favorite(Article::class)->count());
    }

    /** @test */
    public function a_model_delete_favorites_on_deleted_observer()
    {
        $user = User::find(1);
        $user2 = User::find(2);

        $article = Article::first();

        $user->addFavorite($article);
        $user2->addFavorite($article);

        $this->assertDatabaseHas(
            'favorites', [
                'user_id' => $user->id,
                'favoriteable_id' => $article->id,
                'favoriteable_type' => get_class($article)
            ]
        );

        $this->assertDatabaseHas(
            'favorites', [
                'user_id' => $user2->id,
                'favoriteable_id' => $article->id,
                'favoriteable_type' => get_class($article)
            ]
        );

        $article->delete();

        $this->assertDatabaseMissing(
            'favorites', [
                'user_id' => $user->id,
                'favoriteable_id' => $article->id,
                'favoriteable_type' => get_class($article)
            ]
        );

        $this->assertDatabaseMissing(
            'favorites', [
                'user_id' => $user2->id,
                'favoriteable_id' => $article->id,
                'favoriteable_type' => get_class($article)
            ]
        );
    }
}
