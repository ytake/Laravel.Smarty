Laravel.Smarty
==============
smarty template engine for laravel

[![Build Status](http://img.shields.io/travis/ytake/Laravel.Smarty/master.svg?style=flat-square)](https://travis-ci.org/ytake/Laravel.Smarty)
[![Coverage Status](http://img.shields.io/coveralls/ytake/Laravel.Smarty/master.svg?style=flat-square)](https://coveralls.io/r/ytake/Laravel.Smarty?branch=master)
[![Dependency Status](https://www.versioneye.com/user/projects/541bfc296936193b68000060/badge.svg?style=flat-square)](https://www.versioneye.com/user/projects/541bfc296936193b68000060)
[![Scrutinizer Code Quality](http://img.shields.io/scrutinizer/g/ytake/Laravel.Smarty.svg?style=flat)](https://scrutinizer-ci.com/g/ytake/Laravel.Smarty/?branch=master)

[![License](http://img.shields.io/packagist/l/ytake/laravel-smarty.svg?style=flat-square)](https://packagist.org/packages/ytake/laravel-smarty)
[![Latest Version](http://img.shields.io/packagist/v/ytake/laravel-smarty.svg?style=flat-square)](https://packagist.org/packages/ytake/laravel-smarty)
[![Total Downloads](http://img.shields.io/packagist/dt/ytake/laravel-smarty.svg?style=flat-square)](https://packagist.org/packages/ytake/laravel-smarty)

[![HHVM Status](https://img.shields.io/hhvm/ytake/laravel-smarty.svg?style=flat-square)](http://hhvm.h4cc.de/package/ytake/laravel-smarty)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/3837c7b1-ea1e-4db1-8189-f556b14f2ce5/mini.png)](https://insight.sensiolabs.com/projects/3837c7b1-ea1e-4db1-8189-f556b14f2ce5)

##Basic
laravelでbladeに加え、smartyを使用することができます。  
bladeの構文をそのまま使用することができ、  
View Facadeを通じてsmartyのmethodすべてが利用可能です。  
easily use all the methods of smarty  

```php
// laravel5 view render
view("template.name");

// laravel blade template render(use Facades)
\View::make('template', ['hello']);
// use smarty method
\View::assign('word', 'hello');  
\View::clearAllAssign(); // smarty method
```
##Install
###for Laravel5
```json
"require": {
  "ytake/laravel-smarty": "2.*"
},
```
### for Laravel4
[Laravel4.2 / Laravel4.1](https://github.com/ytake/Laravel.Smarty/tree/master-4.2)

###example
[registerFilter in ServiceProvider](https://gist.github.com/ytake/e8c834e88473ea3f10e7)  
[registerFilter in Controller](https://gist.github.com/ytake/1a6f1d5312b552bc83ff)  
[layout.sample](https://gist.github.com/ytake/11345539)  
[layout.extends.sample](https://gist.github.com/ytake/11345614)


##Artisan
キャッシュクリア、コンパイルファイルの削除がコマンドラインから行えます。  
smarty's cacheclear, remove compile class from Artisan(cli)
###cache clear
```bash
$ php artisan ytake:smarty-clear-cache
```

| Options  | description |
| ------------- | ------------- |
| --file (-f) | specify file |
| --time (-t) | clear all of the files that are specified duration time |
| --cache_id (-cache) | specified cache_id groups |

###remove compile class
```bash
$ php artisan ytake:smarty-clear-compiled
```

| Options  | description |
| ------------- | ------------- |
| --file (-f) | specify file |
| --compile_id (-compile) | specified compile_id |

###compile
```bash
$ php artisan ytake:smarty-optimize
```
| Options  | description |
| ------------- | ------------- |
| --extension (-e) | specified smarty file extension(default: *.tpl*) |
| --force | compiles template files found in views directory  |

Template Caching
==================
**choose file, memcached, Redis**  
(default file cache driver)  

テンプレートキャッシュドライバにファイルキャッシュ、memcached, Redisが選択できます  
デフォルトは通常のファイルキャッシュになっています  
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

Usage
==================

install後、
app/config配下のapp.phpのproviders配列に以下のnamespaceを追加してください。  
## add providers
```php
'providers' => [
    'Ytake\LaravelSmarty\SmartyServiceProvider'
]
```

## publish configure(for laravel5)
```bash
$ php artisan vendo:publish
```
publish to config directory


views配下にsmartyファイルがあればそれをview templateとし、  
なければ通常通りbladeテンプレートかphpファイルを使用します。  

smartyテンプレート内にも*{{app_path()}}*等のヘルパーそのまま使用できます。  
その場合、delimiterをbladeと同じものを指定しない様にしてください。  
