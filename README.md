# Laravel.Smarty

Smarty Template Engine for Laravel  
(Support for Laravel5.x and Lumen)

[![Build Status](http://img.shields.io/travis/ytake/Laravel.Smarty/master.svg?style=flat-square)](https://travis-ci.org/ytake/Laravel.Smarty)
[![Coverage Status](http://img.shields.io/coveralls/ytake/Laravel.Smarty/master.svg?style=flat-square)](https://coveralls.io/r/ytake/Laravel.Smarty?branch=master)
[![Dependency Status](https://www.versioneye.com/user/projects/541bfc296936193b68000060/badge.svg?style=flat-square)](https://www.versioneye.com/user/projects/541bfc296936193b68000060)
[![Scrutinizer Code Quality](http://img.shields.io/scrutinizer/g/ytake/Laravel.Smarty.svg?style=flat)](https://scrutinizer-ci.com/g/ytake/Laravel.Smarty/?branch=master)

[![License](http://img.shields.io/packagist/l/ytake/laravel-smarty.svg?style=flat-square)](https://packagist.org/packages/ytake/laravel-smarty)
[![Latest Version](http://img.shields.io/packagist/v/ytake/laravel-smarty.svg?style=flat-square)](https://packagist.org/packages/ytake/laravel-smarty)
[![Total Downloads](http://img.shields.io/packagist/dt/ytake/laravel-smarty.svg?style=flat-square)](https://packagist.org/packages/ytake/laravel-smarty)
[![StyleCI](https://styleci.io/repos/24187404/shield)](https://styleci.io/repos/24187404)

[![HHVM Status](https://img.shields.io/hhvm/ytake/laravel-smarty.svg?style=flat-square)](http://hhvm.h4cc.de/package/ytake/laravel-smarty)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/3837c7b1-ea1e-4db1-8189-f556b14f2ce5/mini.png)](https://insight.sensiolabs.com/projects/3837c7b1-ea1e-4db1-8189-f556b14f2ce5)

## Installation For Laravel
Require this package with Composer

```bash
$ composer require ytake/laravel-smarty
```

or composer.json 

```json
"require": {
  "ytake/laravel-smarty": "~2.0"
},
```

add Laravel.Smarty Service Providers

your config/app.php 
```php
'providers' => [
    // add smarty extension
    \Ytake\LaravelSmarty\SmartyServiceProvider::class, 
    // add artisan commands  
    \Ytake\LaravelSmarty\SmartyConsoleServiceProvider::class, 
]
```

## Installation For Lumen
Require this package with Composer

```bash
$ composer require ytake/laravel-smarty
```

or composer.json 

```json
"require": {
  "ytake/laravel-smarty": "~2.0"
},
```

register Laravel.Smarty Service Providers

your bootstrap/app.php 
```php
$app->configure('ytake-laravel-smarty');
$app->register(Ytake\LaravelSmarty\SmartyServiceProvider::class);
$app->register(Ytake\LaravelSmarty\SmartyConsoleServiceProvider::class);
```

## Configuration

### publish configuration file (for Laravel5)

```bash
$ php artisan vendor:publish
```

publish to config directory

Of Course, Blade Template can also be used to Render Engine.

smartyテンプレート内にも*{{app_path()}}*等のヘルパーそのまま使用できます。  
その場合、delimiterをbladeと同じものを指定しない様にしてください。  

### configuration file (for Lumen)

Copy the `vendor/ytake/laravel-smarty/src/config/ytake-laravel-smarty.php` file to your local config directory

### config for Production
edit config/ytake-laravel-smarty.php

```php
    // enabled smarty template cache
    'caching' => true, // default false
    
    // disabled smarty template compile
    'force_compile' => false, // default true(for develop)
```

Or

add .env file
```
SMARTY_CACHE=true
SMARTY_COMPILE=false
```

edit config/ytake-laravel-smarty.php
 
```php
    'caching' => env('SMARTY_CACHING', false),
   
    'force_compile' => env('SMARTY_FORCE_COMPILE', true),
```

and more..!

## Laravel.Smarty Package Optimize (Optional for production)
**required config/compile.php**

```php
'providers' => [
    //
    \Ytake\LaravelSmarty\SmartyCompileServiceProvider::class
],
```

### use optimize command
for production
```bash
$ php artisan optimize
```

for develop/debug
```bash
$ php artisan optimize --force
```

## Basic
easily use all the methods of Smarty  

```php
// laravel5 view render
view("template.name");

// Laravel blade template render(use Facades)
\View::make('template', ['hello']);
// use Smarty method

\View::assign('word', 'hello');  
\View::clearAllAssign(); // smarty method
```

## View Composer, and View Share

```php
$this->app['view']->composer('index', function (View $view) {
    $view->with('message', 'enable smarty');
});
$this->app['view']->share('title', 'laravel-smarty');

```

```html
Hello Laravel.Smarty

{$title}

{$message}
```

## Artisan
キャッシュクリア、コンパイルファイルの削除がコマンドラインから行えます。  
smarty's cache clear, remove compile class from Artisan(cli)

### Template cache clear
```bash
$ php artisan ytake:smarty-clear-cache
```

| Options  | description |
| ------------- | ------------- |
| --file (-f) | specify file |
| --time (-t) | clear all of the files that are specified duration time |
| --cache_id (-cache) | specified cache_id groups |

### Remove compile file

```bash
$ php artisan ytake:smarty-clear-compiled
```

| Options  | description |
| ------------- | ------------- |
| --file (-f) | specify file |
| --compile_id (-compile) | specified compile_id |

### Template Compiler
 
```bash
$ php artisan ytake:smarty-optimize
```

| Options  | description |
| ------------- | ------------- |
| --extension (-e) | specified smarty file extension(default: *.tpl*) |
| --force | compiles template files found in views directory  |

## Template Caching

**choose file, memcached, Redis**  
(default file cache driver)  

```php
// smarty cache driver "file", "memcached", "redis"
'cache_driver' => 'file',

// memcached servers
'memcached' => [
    [
        'host' => '127.0.0.1',
        'port' => 11211,
        'weight' => 100
    ],
],

// redis configure
'redis' => [
    [
        'host' => '127.0.0.1',
        'port' => 6379,
        'database' => 0,
    ],
],
```

### example
[registerFilter in ServiceProvider](https://gist.github.com/ytake/e8c834e88473ea3f10e7)  
[registerFilter in Controller](https://gist.github.com/ytake/1a6f1d5312b552bc83ff)  
[layout.sample](https://gist.github.com/ytake/11345539)  
[layout.extends.sample](https://gist.github.com/ytake/11345614)
