# Laravel Favorite (Laravel 5 Package)

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Packagist Downloads][ico-downloads]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]

**Allows Laravel Eloquent models to implement a 'favorite' or 'remember' or 'follow' feature.**

## Index

- [Installation](#installation)
- [Models](#models)
- [Usage](#usage)
- [Testing](#testing)
- [Change log](#change-log)
- [Contributions](#contributions)
	- [Pull Requests](#pull-requests)
- [Security](#security)
- [Credits](#credits)
- [License](#license)

## Installation

1) Install the package via Composer

```bash
$ composer require christiankuri/laravel-favorite
```

2) In Laravel >=5.5 this package will automatically get registered. For older versions, update your `config/app.php` by adding an entry for the service provider.

```php
'providers' => [
    // ...
    ChristianKuri\LaravelFavorite\FavoriteServiceProvider::class,
];
```

3) Publish the database from the command line:

```shell
php artisan vendor:publish --provider="ChristianKuri\LaravelFavorite\FavoriteServiceProvider"
```

4) Migrate the database from the command line:

```shell
php artisan migrate
```

## Models

Your User model should import the `Traits/Favoriteability.php` trait and use it, that trait allows the user to favorite the models.
(see an example below):

```php
use ChristianKuri\LaravelFavorite\Traits\Favoriteability;

class User extends Authenticatable
{
	use Favoriteability;
}
```

Your models should import the `Traits/Favoriteable.php` trait and use it, that trait have the methods that you'll use to allow the model be favoriteable.
In all the examples I will use the Post model as the model that is 'Favoriteable', thats for example propuses only.
(see an example below):

```php
use ChristianKuri\LaravelFavorite\Traits\Favoriteable;

class Post extends Model
{
    use Favoriteable;
}
```

That's it ... your model is now **"favoriteable"**!
Now the User can favorite models that have the favoriteable trait.

## Usage

The models can be favorited with and without an authenticated user
(see examples below):

### Add to favorites and remove from favorites:

If no param is passed in the favorite method, then the model will asume the auth user.

``` php
$post = Post::find(1);
$post->addFavorite(); // auth user added to favorites this post
$post->removeFavorite(); // auth user removed from favorites this post
$post->toggleFavorite(); // auth user toggles the favorite status from this post
```

If a param is passed in the favorite method, then the model will asume the user with that id.

``` php
$post = Post::find(1);
$post->addFavorite(5); // user with that id added to favorites this post
$post->removeFavorite(5); // user with that id removed from favorites this post
$post->toggleFavorite(5); // user with that id toggles the favorite status from this post
```

The user model can also add to favorites and remove from favrites:

``` php
$user = User::first();
$post = Post::first();
$user->addFavorite($post); // The user added to favorites this post
$user->removeFavorite($post); // The user removed from favorites this post
$user->toggleFavorite($post); // The user toggles the favorite status from this post
```

### Return the favorite objects for the user:

A user can return the objects he marked as favorite.
You just need to pass the **class** in the `favorite()` method in the `User` model.

``` php
$user = Auth::user();
$user->favorite(Post::class); // returns a collection with the Posts the User marked as favorite
```

### Return the favorites count from an object:

You can return the favorites count from an object, you just need to return the `favoritesCount` attribute from the model

``` php
$post = Post::find(1);
$post->favoritesCount; // returns the number of users that have marked as favorite this object.
```

### Return the users who marked this object as favorite

You can return the users who marked this object, you just need to call the `favoritedBy()` method in the object

``` php
$post = Post::find(1);
$post->favoritedBy(); // returns a collection with the Users that marked the post as favorite.
```

### Check if the user already favorited an object

You can check if the Auth user have already favorited an object, you just need to call the `isFavorited()` method in the object

``` php
$post = Post::find(1);
$post->isFavorited(); // returns a boolean with true or false.
```

## Testing

The package have integrated testing, so everytime you make a pull request your code will be tested.

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributions

Contributions are **welcome** and will be fully **credited**.
We accept contributions via Pull Requests on [Github](https://github.com/ChristianKuri/laravel-favorite).

### Pull Requests

- **[PSR-2 Coding Standard](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)** - Check the code style with ``$ composer check-style`` and fix it with ``$ composer fix-style``.

- **Add tests!** - Your patch won't be accepted if it doesn't have tests.

- **Document any change in behaviour** - Make sure the `README.md` and any other relevant documentation are kept up-to-date.

- **Consider our release cycle** - We try to follow [SemVer v2.0.0](http://semver.org/). Randomly breaking public APIs is not an option.

- **Create feature branches** - Don't ask us to pull from your master branch.

- **One pull request per feature** - If you want to do more than one thing, send multiple pull requests.

- **Send coherent history** - Make sure each individual commit in your pull request is meaningful. If you had to make multiple intermediate commits while developing, please [squash them](http://www.git-scm.com/book/en/v2/Git-Tools-Rewriting-History#Changing-Multiple-Commit-Messages) before submitting.

## Security

Please report any issue you find in the issues page.
Pull requests are welcome.

## Credits

- [Christian Kuri][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/ChristianKuri/laravel-favorite.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/ChristianKuri/laravel-favorite/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/ChristianKuri/laravel-favorite.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/ChristianKuri/laravel-favorite.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/ChristianKuri/laravel-favorite.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/ChristianKuri/laravel-favorite
[link-travis]: https://travis-ci.org/ChristianKuri/laravel-favorite
[link-scrutinizer]: https://scrutinizer-ci.com/g/ChristianKuri/laravel-favorite/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/ChristianKuri/laravel-favorite
[link-downloads]: https://packagist.org/packages/ChristianKuri/laravel-favorite
[link-author]: https://github.com/ChristianKuri
[link-contributors]: ../../contributors
