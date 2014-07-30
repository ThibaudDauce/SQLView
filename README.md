SQLView
=======

[![Build Status](https://img.shields.io/travis/ThibaudDauce/SQLView/master.svg?style=flat)](https://travis-ci.org/ThibaudDauce/SQLView)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat)](LICENSE.md)

## Introduction

SQLView package allows you to easily create view for your database.

## Installation
[PHP](https://php.net) 5.4+ and [Laravel](http://laravel.com) 4.2+ are required.

To get the latest version of SQLView, simply require `"thibaud-dauce/sqsl-view": "0.*"` in your `composer.json` file. You'll then need to run `composer install` or `composer update` to download it and have the autoloader updated.

Once SQLView is installed, you need to register the service provider. Open up `app/config/app.php` and add the following to the `providers` key.

* `'ThibaudDauce\SQLView\SQLViewServiceProvider'`

You can register the `SQLView` facade in the `aliases` key of your `app/config/app.php` file if you like.

* `'SQLView' => 'ThibaudDauce\SQLView\Facades\SQLView'`

## Usage

Create a migration file as usual with artisan:
`php artisan migrate:make add_user_view`

And then fill it like this:

```php
<?php

use ThibaudDauce\SQLView\Blueprint;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Migrations\Migration;

class AddUserView extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    SQLView::create('user-view', function($view) {

      $query = $view->newQuery('table')->where('id', new Expression(3));
      $view->setQuery($query);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    SQLView::drop('user-view');
  }

}
```

TODO: Add artisan command.
