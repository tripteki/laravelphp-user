<h1 align="center">User</h1>

This package provides implementation of user in repository pattern for Lumen and Laravel besides REST API starterpack of admin management with no intervention to codebase and keep clean.

Getting Started
---

Installation :

```
$ composer require tripteki/laravelphp-user
```

How to use it :

- Put `Tripteki\User\Providers\UserServiceProvider` to service provider configuration list.

- Put `Tripteki\User\Providers\UserServiceProvider::ignoreMigrations()` into `register` provider, then publish migrations file into your project's directory with running (optionally) :

```
php artisan vendor:publish --tag=tripteki-laravelphp-user-migrations
```

- Migrate.

```
$ php artisan migrate
```

- Sample :

```php
use Tripteki\User\Contracts\Repository\Admin\IUserRepository as IUserAdminRepository;

$userAdminRepository = app(IUserAdminRepository::class);

// $userAdminRepository->create([ "name" => "...", "email" => "...", "password" => "...", ]); //
// $userAdminRepository->delete("identifier"); //
// $userAdminRepository->update("identifier", [ "name" => "...", "email" => "...", "password" => "...", ]); //
// $userAdminRepository->get("identifier"); //
// $userAdminRepository->all(); //
```

- Generate swagger files into your project's directory with putting this into your annotation configuration (optionally) :

```
base_path("app/Http/Controllers/Admin/User")
```

Usage
---

`php artisan adminer:install:user`

Author
---

- Trip Teknologi ([@tripteki](https://linkedin.com/company/tripteki))
- Hasby Maulana ([@hsbmaulana](https://linkedin.com/in/hsbmaulana))
