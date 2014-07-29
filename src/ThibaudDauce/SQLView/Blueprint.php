<?php namespace ThibaudDauce\SQLView;

use Closure;
use Illuminate\Support\Fluent;
use Illuminate\Database\Connection;
use Illuminate\Database\Schema\Grammars\Grammar;

class Blueprint {

  /**
   * The view the blueprint describes.
   *
   * @var string
   */
  protected $view;

  /**
   * The commands that should be run for the table.
   *
   * @var array
   */
  protected $commands = array();

  /**
   * Create a new view blueprint.
   *
   * @param  string   $view
   * @param  \Closure $callback
   * @return void
   */
  public function __construct($view, Closure $callback = null)
  {
    $this->view = $view;

    if ( ! is_null($callback)) $callback($this);
  }

  /**
   * Execute the blueprint against the database.
   *
   * @param  \Illuminate\Database\Connection        $connection
   * @param  \ThibaudDauce\SQLView\Grammars\Grammar $grammar
   * @return void
   */
  public function build(Connection $connection, Grammar $grammar)
  {
    
  }

  /**
   * Indicate that the view needs to be created.
   *
   * @return \Illuminate\Support\Fluent
   */
  public function create()
  {
    return $this->addCommand('create');
  }

  /**
   * Add a new command to the blueprint.
   *
   * @param  string $name
   * @param  array  $parameters
   * @return \Illuminate\Support\Fluent
   */
  protected function addCommand($name, array $parameters = array())
  {
    $this->commands[] = $command = $this->createCommand($name, $parameters);

    return $command;
  }

  /**
   * Create a new Fluent command.
   *
   * @param  string  $name
   * @param  array   $parameters
   * @return \Illuminate\Support\Fluent
   */
  protected function createCommand($name, array $parameters = array())
  {
    return new Fluent(array_merge(compact('name'), $parameters));
  }
}
