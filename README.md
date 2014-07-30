SQLView
=======

# Introduction

SQLView package allows you to easily create view for your database.

# Installation
[PHP](https://php.net) 5.4+ and [Laravel](http://laravel.com) 4.2+ are required.

To get the latest version of SQLView, simply require `"thibaud-dauce/sqsl-view": "0.*"` in your `composer.json` file. You'll then need to run `composer install` or `composer update` to download it and have the autoloader updated.

Once SQLView is installed, you need to register the service provider. Open up `app/config/app.php` and add the following to the `providers` key.

* `'ThibaudDauce\SQLView\SQLViewServiceProvider'`

You can register the `SQLView` facade in the `aliases` key of your `app/config/app.php` file if you like.

* `'SQLView' => 'ThibaudDauce\SQLView\Facades\SQLView'`
