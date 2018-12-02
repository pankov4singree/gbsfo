<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## About GBSFO-test

 - git clone https://github.com/pankov4singree/gbsfo.git
 - cd gbsfo
 - composer install
 - cp .env.example .env
 - php artisan key:generate
 - create domain alias
 - create DB (GBSFO) utf_8_general_ci
 - set connect params in .env (default name:root pass:"" host:127.0.0.1)
 - php artisan migrate
 - php artisan db:seed
 - go to browser and login to admin (admin@email.com / test)