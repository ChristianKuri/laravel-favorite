<?php

namespace ChristianKuri\LaravelFavorite\Test;

use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use ChristianKuri\LaravelFavorite\FavoriteServiceProvider;
use ChristianKuri\LaravelFavorite\Models\Favorite;
use ChristianKuri\LaravelFavorite\Test\Models\Article;
use ChristianKuri\LaravelFavorite\Test\Models\Post;
use ChristianKuri\LaravelFavorite\Test\Models\User;

abstract class TestCase extends OrchestraTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    protected function checkRequirements()
    {
        parent::checkRequirements();

        collect($this->getAnnotations())->filter(function ($location) {
            return in_array('!Travis', array_get($location, 'requires', []));
        })->each(function ($location) {
            getenv('TRAVIS') && $this->markTestSkipped('Travis will not run this test.');
        });
    }

    protected function getPackageProviders($app)
    {
        return [
            FavoriteServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');

        $app['config']->set('database.connections.sqlite', [
            'driver'   => 'sqlite',
            'database' => $this->getTempDirectory().'/database.sqlite',
            'prefix'   => '',
        ]);

        if (starts_with($app->version(), '5.1')) {
            $app['config']->set('auth.model', User::class);
        } else {
            $app['config']->set('auth.providers.users.model', User::class);
        }

        $app['config']->set('app.key', '6rE9Nz59bGRbeMATftriyQjrpF7DcOQm');
    }

    protected function setUpDatabase()
    {
        $this->resetDatabase();

        $this->CreateFavoritesTable();

        $this->createTables('articles', 'posts', 'users');
        $this->seedModels(Article::class, Post::class, User::class);
    }

    protected function resetDatabase()
    {
        file_put_contents($this->getTempDirectory().'/database.sqlite', null);
    }

    protected function CreateFavoritesTable()
    {
        include_once '__DIR__'.'/../migrations/create_favorites_table.php';

        (new \CreateFavoritesTable())->up();
    }

    public function getTempDirectory()
    {
        return __DIR__.'/temp';
    }

    protected function createTables(...$tableNames)
    {
        collect($tableNames)->each(function ($tableName) {
            $this->app['db']->connection()->getSchemaBuilder()->create($tableName, function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->nullable();
                $table->string('text')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        });
    }

    protected function seedModels(...$modelClasses)
    {
        collect($modelClasses)->each(function ($modelClass) {
            foreach (range(1, 10) as $index) {
                $modelClass::create(['name' => "name {$index}"]);
            }
        });
    }
}
