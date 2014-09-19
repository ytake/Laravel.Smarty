Laravel.Smarty
==============
smarty template engine for laravel

[![Build Status](https://travis-ci.org/ytake/Laravel.Smarty.svg?branch=develop)](https://travis-ci.org/ytake/Laravel.Smarty)
[![Coverage Status](https://coveralls.io/repos/ytake/Laravel.Smarty/badge.png?branch=develop)](https://coveralls.io/r/ytake/Laravel.Smarty?branch=develop)
[![Dependency Status](https://www.versioneye.com/user/projects/541bfc296936193b68000060/badge.svg?style=flat)](https://www.versioneye.com/user/projects/541bfc296936193b68000060)
[![Code Climate](https://codeclimate.com/github/ytake/Laravel.Smarty/badges/gpa.svg)](https://codeclimate.com/github/ytake/Laravel.Smarty)
##install 導入方法
###Laravel4.2
```json
    "require": {
        "ytake/laravel-smarty": "1.2.*"
    },
```

##Basic
smarty template for laravel4  

laravel4でsmartyを使用できます。  
bladeの構文をそのまま使用することができ、  
それに加え、View Facadeを通じてsmartyのmethodはすべて利用可能です。  
easily use all the methods of smarty  
###required array short syntax!
```php
// laravel4 blade template render
View::make('template', ['hello']);
// use smarty method
View::assign('word', 'hello');  
View::clearAllAssign(); // smarty method
```

###example
[registerFilter in ServiceProvider](https://gist.github.com/ytake/e8c834e88473ea3f10e7)  
[registerFilter in Controller](https://gist.github.com/ytake/1a6f1d5312b552bc83ff)
##Artisan
キャッシュクリア、コンパイルファイルの削除がコマンドラインから行えます。  
smarty's cacheclear, remove compile class from Artisan(cli)
###cache clear
```bash
$ php artisan ytake:smarty-clear-cache
```
Options:  
 --file (-f)           specify file  
 --time (-t)           clear all of the files that are specified duration time  
 --cache_id (-cache)   specified cache_id groups
 
###remove compile class
```bash
$ php artisan ytake:smarty-clear-compiled
```
Options:  
 --file (-f)             specify file  
 --compile_id (-compile) specified compile_id
 
###compile 
```bash
$ php artisan ytake:smarty-optimize
```
Options:  
 --extension (-e)             specified smarty file extension(default: *.tpl*)  
 --force                      compiles template files found in views directory

usage 使い方
==================

install後、
app/config配下のapp.phpのproviders配列に以下のnamespaceを追加してください。  
add providers
```php
'providers' => [
    'Ytake\LaravelSmarty\SmartyServiceProvider'
]
```

configファイルをpublishします。  
publish configure
```bash
$ php artisan config:publish ytake/laravel-smarty
```
app/config/packages配下に追加されます。  
publish to app/config/packages


views配下にsmartyファイルがあればそれをテンプレートと使用し、  
なければ通常通りbladeテンプレートかphpファイルを使用します。  

smartyテンプレート内にも*{{app_path()}}*等のヘルパーそのまま使用できます。  
その場合、delimiterをbladeと同じものを指定しない様にしてください。  

configファイルでこれらの指定が可能です。  

sample
======================
[layout.sample](https://gist.github.com/ytake/11345539)  
[layout.extends.sample](https://gist.github.com/ytake/11345614)
