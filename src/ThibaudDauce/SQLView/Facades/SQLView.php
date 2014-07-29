<?php namespace ThibaudDauce\SQLView\Facades;

use Illuminate\Support\Facades\Facade;

class SQLView extends Facade {

  protected static function getFacadeAccessor() { return 'sql-view'; }
}
