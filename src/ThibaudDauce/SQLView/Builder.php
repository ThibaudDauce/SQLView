<?php namespace ThibaudDauce\SQLView;

use Closure;
use ThibaudDauce\SQLView\Grammars\Grammar;

class Builder {

  protected $connection;
  protected $grammar;

  /**
   * Create a new database View manager.
   *
   * @return void
   */
  public function __construct()
  {
    $this->connection = \DB::connection();
    $this->grammar = new Grammar;
  }

  /**
   * Get the database connection.
   *
   * @return \Illuminate\Database\Connection
   */
  protected function getConnection()
  {
    return $this->connection;
  }

  /**
   * Create a new view on the schema.
   *
   * @param  string    $view
   * @param  \Closure  $callback
   * @return \ThibaudDauce\SQLView\Blueprint
   */
  public function create($view, Closure $callback)
  {
    $blueprint = new Blueprint($view, $this->getConnection());

    $blueprint->create();

    $callback($blueprint);

    $this->build($blueprint);
  }

  /**
   * Execute the blueprint to build / modify the view.
   *
   * @param  \ThibaudDauce\SQLView\Blueprint  $blueprint
   * @return void
   */
  protected function build(Blueprint $blueprint)
  {
    $blueprint->build($this->getConnection(), $this->grammar);
  }
}
